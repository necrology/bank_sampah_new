<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SampahController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\Jadwal_penimbanganController;
use App\Http\Controllers\Jadwal_periodeController;
use App\Http\Controllers\PenimbanganController;
use App\Http\Controllers\HomeController;
use App\Models\Penimbangan;
use Illuminate\Support\Facades\Session;

//API LOGIN
// Route::post('login', [AuthController::class, 'login']);

// SAMPAH API
Route::get('getSampah', [SampahController::class, 'index']);

Route::post('addDataSampah', [SampahController::class, 'addDataSampah']);

Route::delete('/deleteDataSampah/{id}', [SampahController::class, 'destroy']);

Route::put('/updateDataSampah/{id}', [SampahController::class, 'update']);

// NASABAH API
Route::get('getNasabah', [NasabahController::class, 'index']);

Route::post('addDataNasabah', [NasabahController::class, 'addDataNasabah']);

Route::delete('/deleteDataNasabah/{id}', [NasabahController::class, 'destroy']);

Route::put('/updateDataNasabah/{id}', [NasabahController::class, 'update']);

Route::get('getProfile', [NasabahController::class, 'getProfile']);

//DASHBOARD
Route::get('dashboard1', [NasabahController::class, 'dashboard1']);

Route::get('chart1', [NasabahController::class, 'chart1']);

Route::get('chart2', [NasabahController::class, 'chart2']);

Route::get('leaderboard', [NasabahController::class, 'leaderboard']);

// JADWAL PENIMBANGAN API
Route::get('getJadwal', [Jadwal_penimbanganController::class, 'index']);

Route::post('addDataJadwal', [Jadwal_penimbanganController::class, 'addDataJadwal']);

Route::delete('/deleteDataJadwal/{id}', [Jadwal_penimbanganController::class, 'destroy']);

Route::put('/updateDataJadwal/{id}', [Jadwal_penimbanganController::class, 'update']);

// JADWAL PENDAFTARAN PENIMBANGAN API
Route::get('getJadwalPendaftaran', [Jadwal_penimbanganController::class, 'getJadwalPendaftaran']);

Route::get('fetchJadwalPeriode', [Jadwal_penimbanganController::class, 'fetchJadwalPeriode']);

// JADWAL PERIODE API
Route::get('getJadwalPeriode', [Jadwal_periodeController::class, 'index']);

Route::post('addDataJadwalPeriode', [Jadwal_periodeController::class, 'addDataJadwalPeriode']);

Route::delete('/deleteDataJadwalPeriode/{id}', [Jadwal_periodeController::class, 'destroy']);

Route::put('/updateDataJadwalPeriode/{id}', [Jadwal_periodeController::class, 'update']);


// PENIMBANGAN API
Route::get('getPenimbangan', [PenimbanganController::class, 'index']);

Route::get('getPenimbanganNasabah', [PenimbanganController::class, 'getPenimbanganNasabah']);

Route::get('fetchJadwal', [PenimbanganController::class, 'fetchJadwal']);

Route::get('fetchSampah', [PenimbanganController::class, 'fetchSampah']);

Route::post('fetchSampahById', [PenimbanganController::class, 'fetchSampahById']);

Route::post('addDataPenimbangan', [PenimbanganController::class, 'addDataPenimbangan']);

Route::delete('/deleteDataPenimbangan/{id}', [PenimbanganController::class, 'destroy']);

Route::put('/updateDataPenimbangan/{id}', [PenimbanganController::class, 'update']);

// //Get Session
// Route::get('session', [AuthController::class, 'session']);