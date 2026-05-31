<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    protected $signature = 'booking:update-status';
    protected $description = 'Otomatis update status booking telat dan kirim webhook';

    public function handle()
    {
        $now = Carbon::now();
        $batasWaktuToleransi = $now->copy()->subMinutes(30);

        // [LOG SPY]: Cek parameter overtime
        Log::info("[CRON UPDATE-STATUS] Cek Overtime. Waktu sekarang: {$now->toDateTimeString()}. Cari end_date <= {$batasWaktuToleransi->toDateTimeString()}");

        // HANYA FOKUS KE DETEKSI TELAT (IN_USE -> LATE)
        $lateBookings = Booking::with(['user', 'vehicle', 'driver'])
               ->where('status', 'in_use')
               ->where('end_date', '<=', $batasWaktuToleransi)
               ->get();

        Log::info("[CRON UPDATE-STATUS] Ditemukan " . $lateBookings->count() . " booking yang OVERTIME.");

        if ($lateBookings->isNotEmpty()) {
            foreach ($lateBookings as $booking) {
                $booking->status = 'late';
                $booking->save();

                $this->sendLateWebhookToN8n($booking, $now);
                
                $this->info('🚨 OVERTIME: Status LATE & Webhook terkirim untuk ' . $booking->booking_code);
                Log::info("[CRON UPDATE-STATUS] Berhasil update & kirim Webhook OVERTIME untuk: {$booking->booking_code}");
            }
        }
    }

    private function sendLateWebhookToN8n($booking, $now)
    {
        // (Isi function ini tetap sama persis)
        $webhookUrl = env('N8N_WEBHOOK_URL');
        $adminPhone = env('ADMIN_PHONE', '081234567890');

        if (!$webhookUrl) return;

        $endDate = Carbon::parse($booking->end_date);
        $lateMinutes = $endDate->diffInMinutes($now);
        $lateHours = ceil($lateMinutes / 60);
        $dendaPerJam = 50000; 
        $totalDendaSementara = $lateHours * $dendaPerJam;

        $payload = [
            'event'             => 'booking_late',
            'booking_code'      => $booking->booking_code,
            'customer_name'     => $booking->user->name ?? 'Customer',
            'customer_phone'    => $booking->user->phone_number ?? $booking->user->phone ?? '', 
            'admin_phone'       => $adminPhone,
            'has_driver'        => $booking->driver_id ? true : false,
            'driver_phone'      => $booking->driver->phone_number ?? null,
            'vehicle_name'      => $booking->vehicle->name ?? 'Kendaraan',
            'end_date'          => $endDate->format('Y-m-d H:i:s'),
            'late_duration'     => $lateHours . ' Jam',
            'late_fee_per_hour' => $dendaPerJam,
            'current_late_fee'  => $totalDendaSementara,
            'warning_message'   => "Masa sewa Anda telah melewati batas waktu pengembalian pada " . $endDate->format('d M Y, H:i') . " WIB. Anda dikenakan denda keterlambatan sebesar Rp " . number_format($dendaPerJam, 0, ',', '.') . " per jam. Estimasi denda saat ini adalah Rp " . number_format($totalDendaSementara, 0, ',', '.') . "."
        ];

        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('GAGAL KIRIM WEBHOOK LATE N8N: ' . $e->getMessage());
        }
    }
}