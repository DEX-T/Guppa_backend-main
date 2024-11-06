<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

\Illuminate\Support\Facades\Schedule::command('sanctum:prune-expired --hours=24')->daily();
\Illuminate\Support\Facades\Schedule::command('guppa_jobs:update-relevance-score')->everySixHours();
