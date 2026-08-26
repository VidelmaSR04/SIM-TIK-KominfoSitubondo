<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\CpanelController;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ServerRegistrationController;

Route::get('/qr/show/{id}', [QrCodeController::class, 'show'])->name('qr.show');
Route::get('/qr/download/{id}', [QrCodeController::class, 'download'])->name('qr.download');

// ============= REGISTER SERVER =============
Route::get('/register-server', [ServerRegistrationController::class, 'create'])->name('register.server');
Route::post('/register-server', [ServerRegistrationController::class, 'store'])->name('register.server.store');

// ============= DASHBOARD =============
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// ============= SERVER ROUTES =============
Route::delete('/server/{id}/remove-image', [ServerController::class, 'removeImage'])->name('server.removeImage');
Route::resource('server', ServerController::class)->except(['show']);

// Custom routes untuk server
Route::get('/server/{id}/pdf', [ServerController::class, 'exportPdf'])->name('server.pdf');
Route::get('/detailserver/{id}', [ServerController::class, 'show'])->name('detailserver');

// ============= LENGKAPI DATA (Admin melengkapi data dari user) =============
Route::get('/server/{id}/lengkapi', [ServerController::class, 'lengkapi'])->name('server.lengkapi');
Route::put('/server/{id}/lengkapi', [ServerController::class, 'updateLengkapi'])->name('server.lengkapi.update');

// Redirect /inputdata ke server.create
Route::get('/inputdata', function () {
    return redirect()->route('server.create');
})->name('inputdata');

// ============= CPANEL =============
Route::get('/cpanel', function () {
    return view('cpanel');
})->name('cpanel');

// ============= APLIKASI =============
Route::get('/aplikasi', function () {
    return view('aplikasi');
})->name('aplikasi');

// ============= ROUTE TAMBAHAN =============
Route::get('/splp', function () { return view('splp'); })->name('splp');
Route::get('/laporan-tugas', function () { return view('laporan-tugas'); })->name('laporan-tugas');
Route::get('/laporan-noc', function () { return view('laporan-noc'); })->name('laporan-noc');

// ============= WELCOME =============
Route::get('/', function () {
    return view('welcome');
});

// ============= PROFILE =============
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============= AUTH (Breeze) =============
require __DIR__.'/auth.php';
