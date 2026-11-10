<?php

use Illuminate\Support\Facades\{Artisan, Log, Schedule, DB};

Schedule::command('filepond:clear')->dailyAt('01:00');
Schedule::command('orders:mark-failed')->dailyAt('01:00');
Schedule::command('log:clear')->monthlyOn(29, '01:00');
