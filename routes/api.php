<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetugasController;
use App\Http\Controllers\Api\LokasiController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\KategoriMasalahController;
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\Api\NotifikasiController;
use App\Http\Controllers\Api\DashboardController;

Route::post('/register', [PetugasController::class, 'register']);

Route::post('/login', [PetugasController::class, 'login']);

Route::get('/petugas', [PetugasController::class, 'index']);

Route::put('/petugas/{id}/approve', [PetugasController::class, 'approve']);

Route::put('/petugas/{id}/reject', [PetugasController::class, 'reject']);

// LOKASI
Route::get('/lokasi', [LokasiController::class, 'index']);

Route::post('/lokasi', [LokasiController::class, 'store']);

Route::put('/lokasi/{id}', [LokasiController::class, 'update']);

Route::delete('/lokasi/{id}', [LokasiController::class, 'destroy']);

// MENU
Route::get('/menu', [MenuController::class, 'index']);

Route::post('/menu', [MenuController::class, 'store']);

Route::put('/menu/{id}', [MenuController::class, 'update']);

Route::delete('/menu/{id}', [MenuController::class, 'destroy']);

// KATEGORI MASALAH
Route::get('/kategori-masalah', [KategoriMasalahController::class, 'index']);

Route::post('/kategori-masalah', [KategoriMasalahController::class, 'store']);

Route::put('/kategori-masalah/{id}', [KategoriMasalahController::class, 'update']);

Route::delete('/kategori-masalah/{id}', [KategoriMasalahController::class, 'destroy']);

// LAPORAN
Route::get('/laporan', [LaporanController::class, 'index']);

Route::post('/laporan', [LaporanController::class, 'store']);

Route::get('/laporan/{id}', [LaporanController::class, 'show']);

Route::put('/laporan/{id}/verifikasi', [LaporanController::class, 'verifikasi']);

// NOTIFIKASI
Route::get('/notifikasi', [NotifikasiController::class, 'index']);

Route::get('/notifikasi/count', [NotifikasiController::class, 'countBelumDibaca']);

Route::put('/notifikasi/{id}/dibaca', [NotifikasiController::class, 'tandaiDibaca']);

// DASHBOARD
Route::get('/dashboard', [DashboardController::class, 'index']);