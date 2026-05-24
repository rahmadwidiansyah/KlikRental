<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        // 1. Set konfigurasi rahasia Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);

        try {
            // 2. Tangkap notifikasi dari Midtrans
            $notif = new Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id; // Ini berisi booking_code kita
            $fraud = $notif->fraud_status;

            // 3. Cari data booking berdasarkan kode uniknya
            $booking = Booking::where('booking_code', $orderId)->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking tidak ditemukan'], 404);
            }

            // 4. Update status berdasarkan aturan resmi Midtrans
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $booking->status = 'pending';
                    } else {
                        $booking->status = 'success';
                    }
                }
            } elseif ($transaction == 'settlement') {
                // Pembayaran sukses/lunas otomatis masuk sini (Virtual Account, QRIS, dll)
                $booking->status = 'success';
            } elseif ($transaction == 'pending') {
                $booking->status = 'pending';
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $booking->status = 'cancelled';
            }

            // 5. Simpan perubahan ke database
            $booking->save();

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}