<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Command bawaan
Schedule::command('booking:update-status')->everyMinute();
Schedule::command('booking:send-reminder')->everyMinute(); 

Schedule::command('booking:monitor-start')->everyMinute();