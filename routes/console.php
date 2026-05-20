<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('bagihasil:autokirim')->lastDayOfMonth('23:30');
Schedule::command('activitylog:clean')->daily();
Schedule::command('pesanan:auto-selesai')->daily(); // ✅ auto selesai pesanan dikirim > 3 hari
