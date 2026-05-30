<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Command bawaan untuk update status in_use & late
Schedule::command('booking:update-status')->everyMinute();

// Command BARU untuk pengingat 2 jam sebelum selesai
Schedule::command('booking:send-reminder')->everyMinute();