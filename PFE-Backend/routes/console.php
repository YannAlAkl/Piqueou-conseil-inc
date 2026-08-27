<?php

use App\Http\Controllers\Admin\NewsletterController;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call([NewsletterController::class, 'retrieveArticles'])->weeklyOn(1, '08:00');

Schedule::call([NewsletterController::class, 'sendToSubscribers'])->weeklyOn(1, '09:00');
