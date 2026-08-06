<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\CpanelController;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\QrCodeController;

Route::get('/qr/download/{id}', [QrCodeController::class, 'download'])->name('qr.download');

Route::get('/regis', function () {
    return view('register'); 
})->name('regis');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('server', ServerController::class)->except(['show']);
Route::get('/server/{id}/pdf', [ServerController::class, 'exportPdf'])->name('server.pdf');

Route::get('/detailserver/{id}', [ServerController::class, 'show'])->name('detailserver');

Route::get('/inputdata', function () {
    return redirect()->route('server.create');
})->name('inputdata');

// cPanel
Route::get('/cpanel', function () {
    return view('cpanel');
})->name('cpanel');

// Aplikasi
Route::get('/aplikasi', function () {
    return view('aplikasi');
})->name('aplikasi');

// Route Tambahan
Route::get('/splp', function () { return view('splp'); })->name('splp');
Route::get('/laporan-tugas', function () { return view('laporan-tugas'); })->name('laporan-tugas');
Route::get('/laporan-noc', function () { return view('laporan-noc'); })->name('laporan-noc');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
