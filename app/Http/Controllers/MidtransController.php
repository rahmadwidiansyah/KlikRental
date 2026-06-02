<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        try {
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // Load relasi sekalian biar gampang ditarik datanya buat payload n8n
            $booking = Booking::with(['user', 'vehicle', 'driver', 'pickupZone', 'dropoffZone'])
                              ->where('booking_code', $orderId)
                              ->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking tidak ditemukan'], 404);
            }

            // Simpan status lama untuk ngecek apakah status benar-benar BERUBAH jadi paid
            $oldStatus = $booking->status;
            $newStatus = $oldStatus;

            // JIKA STATUS SUDAH FINAL (PAID/CANCELLED/COMPLETED), JANGAN UPDATE LAGI
            if (in_array($oldStatus, Booking::TERMINAL_STATUSES)) {
                return response()->json(['message' => 'Status transaksi sudah terminal, tidak ada perubahan dilakukan.']);
            }

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $newStatus = 'pending';
                    } else {
                        $newStatus = 'paid'; 
                    }
                }
            } elseif ($transaction == 'settlement') {
                $newStatus = 'paid';
            } elseif ($transaction == 'pending') {
                $newStatus = 'pending';
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $newStatus = 'cancelled';
            }

            $booking->status = $newStatus;
            $booking->save();

            // TRIGGER WEBHOOK: Hanya tembak kalau statusnya BERUBAH jadi paid
            // Ini mencegah spam WA kalau Midtrans ngirim notif settlement berkali-kali
            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                $this->sendWebhookToN8n($booking);
            }

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            Log::error('MIDTRANS ERROR: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Fungsi khusus untuk memformat data dan menembak webhook ke n8n
     */
    private function sendWebhookToN8n($booking)
    {
        $webhookUrl = env('N8N_WEBHOOK_URL');
        $adminPhone = env('ADMIN_PHONE', '081234567890');

        if (!$webhookUrl) {
            Log::warning('N8N Webhook URL belum diset di .env');
            return;
        }

        // Siapin payload lengkap biar gampang di-routing di n8n
        $payload = [
            'event'          => 'booking_paid',
            'booking_code'   => $booking->booking_code,
            
            // 1. Data Customer 
            'customer_name'  => $booking->user->name ?? 'Customer',
            'customer_phone' => $booking->user->phone_number ?? $booking->user->phone ?? '', 
            
            // 2. Data Admin
            'admin_phone'    => $adminPhone,
            
            // 3. Data Driver 
            'has_driver'     => $booking->driver_id ? true : false,
            'driver_name'    => $booking->driver->name ?? null,
            'driver_phone'   => $booking->driver->phone_number ?? null,
            
            // 4. Detail Sewa
            'vehicle_name'   => $booking->vehicle->name ?? 'Kendaraan',
            'license_plate'  => $booking->vehicle->license_plate ?? 'Belum Diatur', // <-- TAMBAHAN: Data Plat Nomor
            'start_date'     => $booking->start_date,
            'end_date'       => $booking->end_date,
            'pickup_zone'    => $booking->pickupZone->zone_name ?? '-',
            'dropoff_zone'   => $booking->dropoffZone->zone_name ?? '-',
            'total_price'    => $booking->total_price,
        ];

        // Security: Tambahkan Secret Token untuk validasi di sisi n8n
        $n8nSecret = env('N8N_WEBHOOK_SECRET', 'klikrental_secret_token');

        try {
            // Pakai timeout 5 detik biar proses balasan ke server Midtrans nggak gantung
            // kalau misal server n8n lagi mati/lambat
            Http::withHeaders([
                'X-N8N-SECRET' => $n8nSecret
            ])->timeout(5)->post($webhookUrl, $payload);
        } catch (\Exception $e) {
            Log::error('GAGAL KIRIM WEBHOOK N8N: ' . $e->getMessage());
        }
    }
}