<?php

namespace App\Filament\Admin\Resources\Bookings\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Zone;
use App\Models\Promo;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // --- BAGIAN 1: IDENTITAS ---
                Section::make(fn (string $operation) => $operation === 'edit' ? 'Ringkasan Pesanan' : 'Informasi Dasar')
                    ->description(fn (string $operation) => $operation === 'edit' ? 'Data pelanggan dan kendaraan yang sudah terkunci.' : 'Tentukan tipe pesanan dan identitas pelanggan.')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('is_office_order')
                                ->label('Order di Kantor')
                                ->hidden(fn (string $operation) => $operation === 'edit') // Sembunyikan helper saat edit
                                ->helperText('Aktifkan jika pelanggan datang langsung ke kantor (Titik Jemput & Kembali akan otomatis diset ke Kantor).')
                                ->dehydrated(false) // Penting: Jangan simpan ke database karena ini hanya helper UI
                                ->live()
                                ->afterStateUpdated(function (bool $state, Set $set, Get $get) {
                                    if ($state) {
                                        $officeZone = Zone::where('is_office', true)->first();
                                        if ($officeZone) {
                                            $set('pickup_zone_id', $officeZone->id);
                                            $set('dropoff_zone_id', $officeZone->id);
                                        }
                                    }
                                    self::updateTotals($get, $set);
                                }),
                        ]),
                        
                        TextInput::make('booking_code')
                            ->label('Kode Booking')
                            ->default(fn () => 'KR-' . strtoupper(Str::random(6)) . '-' . time())
                            ->required() 
                            ->readOnly(),

                        Select::make('user_id')
                            ->label('Pelanggan')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->disabled(fn (string $operation) => $operation === 'edit') // Kunci saat edit
                            ->preload()
                            ->required(),

                        Select::make('vehicle_id')
                            ->label('Kendaraan')
                            ->options(fn () => Vehicle::all()->mapWithKeys(fn ($v) => [$v->id => "{$v->name} [{$v->license_plate}]"]))
                            ->disabled(fn (string $operation) => $operation === 'edit') // Kunci saat edit
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),

                        Select::make('driver_id')
                            ->label('Supir')
                            ->relationship('driver', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),
                    ]),

                // --- BAGIAN 2: LOGISTIK ---
                Section::make('Logistik & Waktu')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('pickup_zone_id')
                                ->label('Titik Jemput')
                                ->relationship('pickupZone', 'zone_name')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),

                            Select::make('dropoff_zone_id')
                                ->label('Titik Kembali')
                                ->relationship('dropoffZone', 'zone_name')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),

                            DateTimePicker::make('start_date')
                                ->label('Waktu Mulai')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),

                            DateTimePicker::make('end_date')
                                ->label('Waktu Selesai')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),
                        ]),
                    ]),

                // --- BAGIAN 3: BIAYA ---
                Section::make('Kalkulasi Biaya')
                    ->schema([
                        // Field tersembunyi untuk menyimpan rate pajak dari model
                        TextInput::make('tax_rate')
                            ->hidden()
                            ->dehydrated(true),

                        Grid::make(3)->schema([
                            Select::make('promo_id')
                                ->label('Promo')
                                ->relationship('promo', 'code')
                                ->live()
                                ->afterStateUpdated(fn (Set $set, Get $get) => self::updateTotals($get, $set)),

                            TextInput::make('subtotal')->numeric()->readOnly()->prefix('Rp'),
                            TextInput::make('tax_amount')->numeric()->readOnly()->prefix('Rp'),
                            TextInput::make('total_price')->numeric()->readOnly()->prefix('Rp')->extraInputAttributes(['class' => 'font-bold text-primary-600']),

                            Select::make('status')
                                ->options([
                                    'pending' => 'Pending',
                                    'paid' => 'Paid',
                                    'in_use' => 'In Use',
                                    'late' => 'Late',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->required()
                                ->default('pending'),
                        ]),
                    ]),
            ]);
    }

    /**
     * Logika Kalkulasi Otomatis
     */
    protected static function updateTotals(Get $get, Set $set): void
    {
        $data = [
            'vehicle_id' => $get('vehicle_id'),
            'driver_id' => $get('driver_id'),
            'pickup_zone_id' => $get('pickup_zone_id'),
            'dropoff_zone_id' => $get('dropoff_zone_id'),
            'start_date' => $get('start_date'),
            'end_date' => $get('end_date'),
            'promo_code' => $get('promo_id') ? Promo::find($get('promo_id'))?->code : null,
        ];

        // Hanya hitung kalau data wajib sudah lengkap
        if (!$data['vehicle_id'] || !$data['pickup_zone_id'] || !$data['dropoff_zone_id'] || !$data['start_date'] || !$data['end_date']) {
            return;
        }

        try {
            $result = Booking::calculatePricing($data);
            $set('tax_rate', $result['tax_rate']);
            $set('subtotal', $result['subtotal']);
            $set('tax_amount', $result['tax_amount']);
            $set('total_price', $result['total_price']);
        } catch (\Exception $e) {
            // Abaikan error kalkulasi saat form belum stabil
        }
    }
}