<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Commands
use App\Console\Commands\DeleteExpiredUsers;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

// Schedule::call(function () {
    
// }) -> daily();

Schedule::command(DeleteExpiredUsers::class) -> daily();
