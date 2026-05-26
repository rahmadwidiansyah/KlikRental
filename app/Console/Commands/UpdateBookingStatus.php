<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateBookingStatus extends Command
{
    protected $signature = 'booking:update-status';
    protected $description = 'Otomatis update status booking ke in_use atau late berdasarkan waktu';

    public function handle()
    {
        $now = Carbon::now();

        // 1. PAID -> IN_USE
        // Jika status paid dan waktu sekarang sudah melewati start_date
        Booking::where('status', 'paid')
               ->where('start_date', '<=', $now)
               ->update(['status' => 'in_use']);

        // 2. IN_USE -> LATE
        // Jika status in_use dan waktu sekarang sudah melewati end_date + 30 menit
        Booking::where('status', 'in_use')
               ->where('end_date', '<=', $now->copy()->subMinutes(30))
               ->update(['status' => 'late']);

        $this->info('Booking status updated successfully.');
    }
}