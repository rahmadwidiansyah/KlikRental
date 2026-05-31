<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SendReturnReminder extends Command
{
    protected $signature = 'booking:send-reminder';
    protected $description = 'Kirim webhook pengingat 2 jam sebelum waktu sewa habis';

    public function handle()
    {
        $now = Carbon::now();
        $targetTime = $now->copy()->addHours(2);

        // [LOG SPY]: Cek parameter
        Log::info("[CRON SEND-REMINDER] Cek Pengembalian H-2 Jam. Waktu sekarang: {$now->toDateTimeString()}. Cari end_date <= {$targetTime->toDateTimeString()}");

        $bookings = Booking::with(['user', 'vehicle', 'driver'])
            ->where('status', 'in_use')
            ->where('end_date', '<=', $targetTime)
            ->where('end_date', '>', $now)
            ->get();

        Log::info("[CRON SEND-REMINDER] Ditemukan " . $bookings->count() . " booking untuk pengingat pengembalian.");

        if ($bookings->isEmpty()) {
            $this->info('Tidak ada booking yang mendekati waktu pengembalian.');
            return;
        }

        foreach ($bookings as $booking) {
            $cacheKey = 'reminder_sent_' . $booking->id;

            if (!Cache::has($cacheKey)) {
                $this->sendWebhookToN8n($booking);
                Cache::put($cacheKey, true, Carbon::parse($booking->end_date));
                
                $this->info('Webhook pengingat terkirim untuk Booking ID: ' . $booking->booking_code);
                Log::info("[CRON SEND-REMINDER] Berhasil kirim Webhook untuk: {$booking->booking_code}");
            } else {
                Log::info("[CRON SEND-REMINDER] SKIP (Cache Aktif) Webhook untuk: {$booking->booking_code}");
            }
        }
    }

    private function sendWebhookToN8n($booking)
    {
        // (Isi function ini tetap sama persis)
        $webhookUrl = env('N8N_WEBHOOK_URL');

        if (!$webhookUrl) {
            Log::warning('N8N_WEBHOOK_URL belum diset di .env');
            return;
        }

        $payload = [
            'event'          => 'booking_reminder_2_hours',
            'booking_code'   => $booking->booking_code,
            'customer_name'  => $booking->user->name ?? 'Customer',
            'customer_phone' => $booking->user->phone_number ?? $booking->user->phone ?? '', 
            'has_driver'     => $booking->driver_id ? true : false,
            'driver_name'    => $booking->driver->name ?? null,
            'driver_phone'   => $booking->driver->phone_number ?? null,
            'vehicle_name'   => $booking->vehicle->name ?? 'Kendaraan',
            'end_date'       => Carbon::parse($booking->end_date)->format('Y-m-d H:i:s'),
            'time_remaining' => '2 Jam',
        ];

        try {
            Http::timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('GAGAL KIRIM WEBHOOK PENGINGAT N8N: ' . $e->getMessage());
        }
    }
}