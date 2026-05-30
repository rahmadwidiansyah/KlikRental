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
    protected $description = 'Otomatis update status booking ke in_use atau late berdasarkan waktu dan kirim webhook jika telat';

    public function handle()
    {
        $now = Carbon::now();

        // 1. PAID -> IN_USE
        // Jika status paid dan waktu sekarang sudah melewati start_date
        Booking::where('status', 'paid')
               ->where('start_date', '<=', $now)
               ->update(['status' => 'in_use']);

        // 2. IN_USE -> LATE
        // Tarik data booking yang telat (lewat dari end_date + 30 menit) beserta relasinya
        $lateBookings = Booking::with(['user', 'vehicle', 'driver'])
               ->where('status', 'in_use')
               ->where('end_date', '<=', $now->copy()->subMinutes(30))
               ->get();

        if ($lateBookings->isEmpty()) {
            $this->info('Update status: Tidak ada booking yang telat saat ini.');
            return;
        }

        foreach ($lateBookings as $booking) {
            // Update status ke late
            $booking->status = 'late';
            $booking->save();

            // Tembak webhook ke n8n
            $this->sendLateWebhookToN8n($booking, $now);
            
            $this->info('Status diubah ke LATE & Webhook peringatan terkirim untuk: ' . $booking->booking_code);
        }
    }

    private function sendLateWebhookToN8n($booking, $now)
    {
        $webhookUrl = env('N8N_WEBHOOK_LATE_URL');
        $adminPhone = env('ADMIN_PHONE', '081234567890');

        if (!$webhookUrl) {
            Log::warning('N8N_WEBHOOK_LATE_URL belum diset di .env');
            return;
        }

        // --- LOGIKA HITUNG DENDA ---
        $endDate = Carbon::parse($booking->end_date);
        
        // Hitung selisih menit keterlambatan dari jam end_date
        $lateMinutes = $endDate->diffInMinutes($now);
        
        // Dibulatkan ke atas menjadi jam (misal telat 65 menit = dihitung 2 jam)
        $lateHours = ceil($lateMinutes / 60);

        // Atur nominal denda per jam (Bisa disesuaikan dengan aturan bisnismu)
        // Alternatif jika ingin dinamis: $dendaPerJam = $booking->vehicle->price_per_day * 0.10; (10% dari harga sewa)
        $dendaPerJam = 50000; 
        
        // Total denda yang berjalan saat notifikasi dikirim
        $totalDendaSementara = $lateHours * $dendaPerJam;

        // Siapkan payload lengkap untuk n8n
        $payload = [
            'event'             => 'booking_late',
            'booking_code'      => $booking->booking_code,
            
            // Data Customer & Admin
            'customer_name'     => $booking->user->name ?? 'Customer',
            'customer_phone'    => $booking->user->phone_number ?? $booking->user->phone ?? '', 
            'admin_phone'       => $adminPhone,
            
            // Data Driver (Opsional)
            'has_driver'        => $booking->driver_id ? true : false,
            'driver_phone'      => $booking->driver->phone_number ?? null,
            
            // Detail Keterlambatan
            'vehicle_name'      => $booking->vehicle->name ?? 'Kendaraan',
            'end_date'          => $endDate->format('Y-m-d H:i:s'),
            'late_duration'     => $lateHours . ' Jam',
            'late_fee_per_hour' => $dendaPerJam,
            'current_late_fee'  => $totalDendaSementara,
            
            // Pesan peringatan yang siap pakai di WA
            'warning_message'   => "Masa sewa Anda telah melewati batas waktu pengembalian pada " . $endDate->format('d M Y, H:i') . " WIB. Anda dikenakan denda keterlambatan sebesar Rp " . number_format($dendaPerJam, 0, ',', '.') . " per jam. Estimasi denda saat ini adalah Rp " . number_format($totalDendaSementara, 0, ',', '.') . "."
        ];

        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('GAGAL KIRIM WEBHOOK LATE N8N: ' . $e->getMessage());
        }
    }
}