<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/seed-db', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
    return response()->json([
        'message' => 'Database seeded successfully!',
        'output' => \Illuminate\Support\Facades\Artisan::output()
    ]);
});
