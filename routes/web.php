<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-rollbar', function () {
    \Log::debug('Test debug message from route');
    \Log::warning('Test warning message');
    \Log::error('Test error message');

    return 'Test messages sent to Rollbar! Check your Rollbar dashboard.';
});
