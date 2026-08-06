<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', function () { return view('welcome'); })->name('welcome');
Route::get('/login', function () { return view('login'); })->name('login');
Route::get('/register', function () { return view('register'); })->name('register');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Server / Perangkat
Route::get('/server', function () { return view('server'); })->name('server');

// Route Detail Server (Dengan parameter ID agar tombol mata berfungsi)
Route::get('/detailserver/{id?}', function ($id = null) {
    return view('detailserver', ['id' => $id]);
})->name('detailserver');

// cPanel
Route::get('/cpanel', function () { return view('cpanel'); })->name('cpanel');

// Aplikasi
Route::get('/aplikasi', function () { return view('aplikasi'); })->name('aplikasi');

// Route Tambahan
Route::get('/splp', function () { return view('splp'); })->name('splp');
Route::get('/laporan-tugas', function () { return view('laporan-tugas'); })->name('laporan-tugas');
Route::get('/laporan-noc', function () { return view('laporan-noc'); })->name('laporan-noc');
Route::get('/inputdata', function () { return view('inputdata'); })->name('inputdata');