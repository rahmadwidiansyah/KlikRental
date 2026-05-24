<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Midtrans\Config;
use Midtrans\Notification;
use Illuminate\Support\Facades\Log;

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

            $booking = Booking::where('booking_code', $orderId)->first();

            if (!$booking) {
                return response()->json(['message' => 'Booking tidak ditemukan'], 404);
            }

            // PERUBAHAN: Ganti kata 'success' menjadi 'paid' menyesuaikan ENUM database!
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $booking->status = 'pending';
                    } else {
                        $booking->status = 'paid'; 
                    }
                }
            } elseif ($transaction == 'settlement') {
                $booking->status = 'paid'; // <-- Diubah di sini
            } elseif ($transaction == 'pending') {
                $booking->status = 'pending';
            } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $booking->status = 'cancelled';
            }

            $booking->save();

            return response()->json(['message' => 'Notification handled successfully']);

        } catch (\Exception $e) {
            Log::error('MIDTRANS ERROR: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}