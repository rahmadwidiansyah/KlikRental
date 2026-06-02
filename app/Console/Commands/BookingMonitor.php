<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BookingMonitor extends Command
{
    // Nama command disatukan
    protected $signature = 'booking:monitor-all';
    protected $description = 'Master Cron: Pantau jadwal ambil, pengingat pengembalian, dan overtime kendaraan sekaligus.';

    public function handle()
    {
        $now = Carbon::now();
        $this->info("Memulai Master Cron Booking pada: {$now->toDateTimeString()}");
        Log::info("=== [MASTER CRON START] Waktu: {$now->toDateTimeString()} ===");

        // Eksekusi 3 fungsi utama secara berurutan
        $this->checkExpiredPendingBookings($now);
        $this->checkPickupSchedules($now);
        $this->checkReturnReminders($now);
        $this->checkLateBookings($now);

        $this->info("Master Cron selesai dieksekusi!");
        Log::info("=== [MASTER CRON END] ===");
    }

    // ==========================================
    // 0. FUNGSI BATALKAN PENDING (EXPIRE 1 JAM)
    // ==========================================
    private function checkExpiredPendingBookings($now)
    {
        $expiredTime = $now->copy()->subHour();
        
        $expiredBookings = Booking::where('status', 'pending')
            ->where('created_at', '<=', $expiredTime)
            ->get();

        foreach ($expiredBookings as $booking) {
            $booking->status = 'cancelled';
            $booking->save();
            
            Log::info("[MASTER CRON] Pesanan Kadaluwarsa (1 Jam): {$booking->booking_code}");
            $this->info("Batal Otomatis: " . $booking->booking_code);
            // Catatan: Model Event di Booking.php akan otomatis melepaskan Mobil/Supir
        }
    }

    // ==========================================
    // 1. FUNGSI PANTAU JADWAL AMBIL (H-30m & H+10m)
    // ==========================================
    private function checkPickupSchedules($now)
    {
        $pendingStatuses = ['paid', 'confirmed']; 

        // -- REMINDER H-30 MENIT --
        $targetReminderTime = $now->copy()->addMinutes(30);
        $reminderBookings = Booking::with(['user', 'vehicle', 'driver'])
            ->whereIn('status', $pendingStatuses)
            ->where('start_date', '<=', $targetReminderTime)
            ->where('start_date', '>', $now)
            ->get();

        foreach ($reminderBookings as $booking) {
            $cacheKey = 'pickup_reminder_sent_' . $booking->id;
            if (!Cache::has($cacheKey)) {
                $this->buildAndSendWebhook($booking, 'booking_pickup_reminder', $now);
                Cache::put($cacheKey, true, Carbon::parse($booking->start_date)->addHours(2));
                $this->info('✅ Reminder H-30m terkirim: ' . $booking->booking_code);
            }
        }

        // -- ESKALASI H+10 MENIT --
        $targetEscalationTime = $now->copy()->subMinutes(10);
        $batasBawahEscalation = $now->copy()->subHours(12);
        
        $escalationBookings = Booking::with(['user', 'vehicle', 'driver'])
            ->whereIn('status', $pendingStatuses)
            ->where('start_date', '<=', $targetEscalationTime)
            ->where('start_date', '>', $batasBawahEscalation) 
            ->get();

        foreach ($escalationBookings as $booking) {
            $cacheKey = 'pickup_escalation_sent_' . $booking->id;
            if (!Cache::has($cacheKey)) {
                $this->buildAndSendWebhook($booking, 'booking_pickup_escalation', $now);
                Cache::put($cacheKey, true, Carbon::parse($booking->start_date)->addHours(12));
                $this->info('🚨 Eskalasi H+10m terkirim: ' . $booking->booking_code);
            }
        }
    }

    // ==========================================
    // 2. FUNGSI PENGINGAT KEMBALI (H-2 JAM)
    // ==========================================
    private function checkReturnReminders($now)
    {
        $targetTime = $now->copy()->addHours(2);
        $bookings = Booking::with(['user', 'vehicle', 'driver'])
            ->where('status', 'in_use')
            ->where('end_date', '<=', $targetTime)
            ->where('end_date', '>', $now)
            ->get();

        foreach ($bookings as $booking) {
            $cacheKey = 'reminder_sent_' . $booking->id;
            if (!Cache::has($cacheKey)) {
                $this->buildAndSendWebhook($booking, 'booking_reminder_2_hours', $now);
                Cache::put($cacheKey, true, Carbon::parse($booking->end_date));
                $this->info('⚠️ Pengingat H-2 Jam terkirim: ' . $booking->booking_code);
            }
        }
    }

    // ==========================================
    // 3. FUNGSI OVERTIME / TELAT (LEWAT 30 MENIT)
    // ==========================================
    private function checkLateBookings($now)
    {
        $batasWaktuToleransi = $now->copy()->subMinutes(30);
        $lateBookings = Booking::with(['user', 'vehicle', 'driver'])
               ->where('status', 'in_use')
               ->where('end_date', '<=', $batasWaktuToleransi)
               ->get();

        foreach ($lateBookings as $booking) {
            $booking->status = 'late';
            $booking->save();

            $this->buildAndSendWebhook($booking, 'booking_late', $now);
            $this->info('🛑 OVERTIME (LATE) terkirim: ' . $booking->booking_code);
        }
    }

    // ==========================================
    // MASTER WEBHOOK SENDER (DRY PRINCIPLE)
    // ==========================================
    private function buildAndSendWebhook($booking, $eventType, $now)
    {
        $webhookUrl = env('N8N_WEBHOOK_URL');
        $adminPhone = env('ADMIN_PHONE', '081234567890');

        if (!$webhookUrl) {
            Log::warning("N8N_WEBHOOK_URL belum diset. Gagal kirim event: $eventType");
            return;
        }

        // --- Data Global ---
        $customerPhone = $booking->user->phone_number ?? $booking->user->phone ?? '';
        $cleanCustomerPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customerPhone));
        $startDateStr = Carbon::parse($booking->start_date)->format('Y-m-d H:i:s');
        $endDate = Carbon::parse($booking->end_date);
        
        // Kalkulasi keterlambatan
        $lateMinutes = $endDate->diffInMinutes($now);
        $lateHours = ceil($lateMinutes / 60);

        // --- Susun Payload Standar ---
        $payload = [
            'event'            => $eventType,
            'booking_code'     => $booking->booking_code,
            'customer_name'    => $booking->user->name ?? 'Customer',
            'customer_phone'   => $customerPhone,
            'admin_phone'      => $adminPhone,
            'has_driver'       => $booking->driver_id ? true : false,
            'driver_name'      => $booking->driver->name ?? null,
            'driver_phone'     => $booking->driver->phone_number ?? null,
            'vehicle_name'     => $booking->vehicle->name ?? 'Kendaraan',
            'license_plate'    => $booking->vehicle->license_plate ?? 'Belum Diatur', // Tambahan Plat
            'start_date'       => $startDateStr,
            'end_date'         => $endDate->format('Y-m-d H:i:s'),
        ];

        // --- Injeksi Data Spesifik per Event ---
        if ($eventType === 'booking_pickup_reminder') {
            $payload['late_duration'] = $lateHours;
            $payload['message_customer'] = "Halo {$booking->user->name}, pengingat bahwa jadwal sewa {$booking->vehicle->name} Anda akan dimulai dalam 30 menit ke depan. Mohon siapkan dokumen persyaratan untuk proses serah terima.";
            $payload['message_driver'] = $booking->driver_id ? "Halo {$booking->driver->name}, pengingat jadwal tugas Anda untuk pesanan {$booking->booking_code} akan dimulai dalam 30 menit. Pemesan: {$booking->user->name} (wa.me/{$cleanCustomerPhone})." : null;
        } 
        elseif ($eventType === 'booking_pickup_escalation') {
            $payload['target_recipient'] = 'admin';
            $payload['late_duration'] = $lateHours;
            $payload['message'] = "🚨 [ESKALASI] Pesanan {$booking->booking_code} atas nama {$booking->user->name} sudah lewat 10 menit dari jadwal ambil, namun status belum In-Use. Silakan hubungi customer di wa.me/{$cleanCustomerPhone}.";
        }
        elseif ($eventType === 'booking_reminder_2_hours') {
            $payload['time_remaining'] = '2 Jam';
        }
        elseif ($eventType === 'booking_late') {
            $dendaPerJam = 50000; 
            $totalDendaSementara = $lateHours * $dendaPerJam;
            
            $payload['late_duration'] = $lateHours . ' Jam';
            $payload['late_fee_per_hour'] = $dendaPerJam;
            $payload['current_late_fee'] = $totalDendaSementara;
            $payload['warning_message'] = "Masa sewa Anda telah melewati batas waktu pengembalian pada " . $endDate->format('d M Y, H:i') . " WIB. Anda dikenakan denda keterlambatan sebesar Rp " . number_format($dendaPerJam, 0, ',', '.') . " per jam. Estimasi denda saat ini adalah Rp " . number_format($totalDendaSementara, 0, ',', '.') . ".";
        }

        // --- Kirim ke n8n ---
        try {
            Http::timeout(5)->post($webhookUrl, $payload);
            Log::info("[MASTER CRON] Berhasil kirim webhook $eventType untuk: {$booking->booking_code}");
        } catch (\Exception $e) {
            Log::error("[MASTER CRON] GAGAL KIRIM WEBHOOK $eventType N8N: " . $e->getMessage());
        }
    }
}