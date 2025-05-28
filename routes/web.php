<?php

use App\Http\Controllers\DataController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/test-env', function () {
    return env('VITE_DEV_SERVER_URL');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/{any}', function () {
    return view('home'); // Tampilkan Blade yang berisi Vue
})->where('any', '.*');
