<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MonitorBookingStart extends Command
{
    protected $signature = 'booking:monitor-start';
    protected $description = 'Pantau jadwal ambil: Kirim reminder H-30m (Gabungan), dan Eskalasi H+10m ke Admin';

    public function handle()
    {
        $now = Carbon::now();
        $pendingStatuses = ['paid', 'confirmed']; 

        // ==========================================
        // 1. SKENARIO H-30 MENIT (REMINDER GABUNGAN)
        // ==========================================
        $targetReminderTime = $now->copy()->addMinutes(30);
        
        // [LOG SPY]: Cek jam dan parameter pencarian H-30
        Log::info("[CRON MONITOR-START] Cek Reminder H-30. Waktu sekarang: {$now->toDateTimeString()}. Cari start_date <= {$targetReminderTime->toDateTimeString()}");
        
        $reminderBookings = Booking::with(['user', 'vehicle', 'driver'])
            ->whereIn('status', $pendingStatuses)
            ->where('start_date', '<=', $targetReminderTime)
            ->where('start_date', '>', $now)
            ->get();

        Log::info("[CRON MONITOR-START] Ditemukan " . $reminderBookings->count() . " booking untuk Reminder H-30.");

        foreach ($reminderBookings as $booking) {
            $cacheKey = 'pickup_reminder_sent_' . $booking->id;

            if (!Cache::has($cacheKey)) {
                $this->sendWebhook($booking, 'reminder');
                Cache::put($cacheKey, true, Carbon::parse($booking->start_date)->addHours(2));
                
                $this->info('✅ Reminder H-30m (Gabungan) terkirim untuk: ' . $booking->booking_code);
                Log::info("[CRON MONITOR-START] Berhasil kirim Webhook Reminder untuk: {$booking->booking_code}");
            } else {
                Log::info("[CRON MONITOR-START] SKIP (Cache Aktif) Webhook Reminder untuk: {$booking->booking_code}");
            }
        }

        // ==========================================
        // 2. SKENARIO H+10 MENIT (ESKALASI ADMIN)
        // ==========================================
        $targetEscalationTime = $now->copy()->subMinutes(10);
        $batasBawahEscalation = $now->copy()->subHours(12);

        // [LOG SPY]: Cek jam dan parameter pencarian Eskalasi
        Log::info("[CRON MONITOR-START] Cek Eskalasi H+10. Cari start_date antara {$batasBawahEscalation->toDateTimeString()} s/d {$targetEscalationTime->toDateTimeString()}");
        
        $escalationBookings = Booking::with(['user', 'vehicle', 'driver'])
            ->whereIn('status', $pendingStatuses)
            ->where('start_date', '<=', $targetEscalationTime)
            ->where('start_date', '>', $batasBawahEscalation) 
            ->get();

        Log::info("[CRON MONITOR-START] Ditemukan " . $escalationBookings->count() . " booking untuk Eskalasi Admin.");

        foreach ($escalationBookings as $booking) {
            $cacheKey = 'pickup_escalation_sent_' . $booking->id;

            if (!Cache::has($cacheKey)) {
                $this->sendWebhook($booking, 'escalation');
                Cache::put($cacheKey, true, Carbon::parse($booking->start_date)->addHours(12));
                
                $this->info('🚨 Eskalasi H+10m terkirim (Admin) untuk: ' . $booking->booking_code);
                Log::info("[CRON MONITOR-START] Berhasil kirim Webhook Eskalasi untuk: {$booking->booking_code}");
            } else {
                Log::info("[CRON MONITOR-START] SKIP (Cache Aktif) Webhook Eskalasi untuk: {$booking->booking_code}");
            }
        }
    }

    private function sendWebhook($booking, $type)
    {
        // (Isi function sendWebhook tetap sama persis seperti kode aslimu)
        $webhookUrl = env('N8N_WEBHOOK_URL');
        $adminPhone = env('ADMIN_PHONE', '081234567890');

        if (!$webhookUrl) {
            Log::warning('N8N_WEBHOOK_URL belum diset di .env');
            return;
        }

        $startDateStr = Carbon::parse($booking->start_date)->format('Y-m-d H:i:s');
        $customerPhone = $booking->user->phone_number ?? $booking->user->phone ?? '';
        
        $cleanCustomerPhone = preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $customerPhone));

        if ($type === 'reminder') {
            $payload = [
                'event'            => 'booking_pickup_reminder',
                'booking_code'     => $booking->booking_code,
                'vehicle_name'     => $booking->vehicle->name ?? 'Kendaraan',
                'start_date'       => $startDateStr,
                'customer_name'    => $booking->user->name ?? 'Customer',
                'customer_phone'   => $customerPhone,
                'has_driver'       => $booking->driver_id ? true : false,
                'driver_name'      => $booking->driver->name ?? null,
                'driver_phone'     => $booking->driver->phone_number ?? null,
                'message_customer' => "Halo {$booking->user->name}, pengingat bahwa jadwal sewa {$booking->vehicle->name} Anda akan dimulai dalam 30 menit ke depan. Mohon siapkan dokumen persyaratan untuk proses serah terima.",
                'message_driver'   => $booking->driver_id ? "Halo {$booking->driver->name}, pengingat jadwal tugas Anda untuk pesanan {$booking->booking_code} akan dimulai dalam 30 menit. Pemesan: {$booking->user->name} (wa.me/{$cleanCustomerPhone})." : null
            ];
        } else {
            $payload = [
                'event'            => 'booking_pickup_escalation',
                'booking_code'     => $booking->booking_code,
                'customer_name'    => $booking->user->name ?? 'Customer',
                'customer_phone'   => $customerPhone,
                'admin_phone'      => $adminPhone,
                'vehicle_name'     => $booking->vehicle->name ?? 'Kendaraan',
                'has_driver'       => $booking->driver_id ? true : false,
                'driver_name'      => $booking->driver->name ?? null,
                'driver_phone'     => $booking->driver->phone_number ?? null,
                'start_date'       => $startDateStr,
                'target_recipient' => 'admin',
                'message'          => "🚨 [ESKALASI] Pesanan {$booking->booking_code} atas nama {$booking->user->name} sudah lewat 10 menit dari jadwal ambil, namun status belum In-Use. Silakan hubungi customer di wa.me/{$cleanCustomerPhone}."
            ];
        }

        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error("GAGAL KIRIM WEBHOOK $type N8N: " . $e->getMessage());
        }
    }
}