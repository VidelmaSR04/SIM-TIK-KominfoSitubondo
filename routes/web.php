<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManajemenServerController;
use App\Http\Controllers\ServerController;
use App\Http\Controllers\CpanelController;
use App\Http\Controllers\AplikasiController;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\ServerRegistrationController;
use App\Http\Controllers\User\DashboardUserController;
use App\Http\Controllers\User\InputDataUserController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\MasterOptionController;
use App\Http\Controllers\ServerDocumentController;
use App\Http\Controllers\ServerPhotoController;

// QR Code tampilan (di halaman)
Route::get('/qr/show/{id}', [QrCodeController::class, 'show'])->name('qr.show');

// QR Code download (dengan background)
Route::get('/qr/download/{id}', [QrCodeController::class, 'download'])->name('qr.download');

// ============= REGISTER SERVER =============
Route::get('/register-server', [ServerRegistrationController::class, 'create'])->name('register.server');
Route::post('/register-server', [ServerRegistrationController::class, 'store'])->name('register.server.store');

// ============= MANAJEMEN SERVER (ADMIN) — dulu bernama "dashboard" =============
Route::get('/manajemen-server', [ManajemenServerController::class, 'index'])->name('manajemen-server');

// ============= SERVER ROUTES (ADMIN) =============
Route::delete('/server/{id}/remove-image', [ServerController::class, 'removeImage'])->name('server.removeImage');
Route::resource('server', ServerController::class)->except(['show']);

// Custom routes untuk server
Route::get('/server/{id}/pdf', [ServerController::class, 'exportPdf'])->name('server.pdf');
Route::get('/detailserver/{id}', [ServerController::class, 'show'])->name('detailserver');

// ============= LENGKAPI DATA (Admin melengkapi data dari user) =============
Route::get('/server/{id}/lengkapi', [ServerController::class, 'lengkapi'])->name('server.lengkapi');
Route::put('/server/{id}/lengkapi', [ServerController::class, 'updateLengkapi'])->name('server.lengkapi.update');

// Redirect /inputdata ke server.create (form ADMIN, biarkan seperti semula)
Route::get('/inputdata', function () {
    return redirect()->route('server.create');
})->name('inputdata');

// ============= MANAJEMEN SERVER: SUBMENU BARU (ADMIN) =============
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/server-dokumen', [ServerDocumentController::class, 'index'])->name('server.dokumen.index');
    Route::get('/server-foto', [ServerPhotoController::class, 'index'])->name('server.foto.index');
    Route::get('/server-master', [MasterOptionController::class, 'index'])->name('server.master.index');
});

// ============= USER DASHBOARD & INPUT DATA (USER) =============
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboarduser', [DashboardUserController::class, 'index'])->name('user.dashboarduser');
    Route::get('/inputdatauser', [InputDataUserController::class, 'create'])->name('inputdatauser.create');
    Route::post('/inputdatauser', [InputDataUserController::class, 'store'])->name('inputdatauser.store');
});

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

// ============= AUTH REGISTRATION (Separate for admin/user) =============
Route::middleware('guest')->group(function () {
    Route::get('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('/register', [App\Http\Controllers\Auth\RegisteredUserController::class, 'store'])
        ->name('register.store');
});

// ============= AUTH (Breeze - Login, Password Reset, etc.) =============
require __DIR__.'/auth.php';

// ============= ADMIN: MANAJEMEN SERVER (khusus role admin) =============
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/manajemen-server', [ManajemenServerController::class, 'index'])
        ->name('admin.manajemen-server');

    // Manajemen Pengguna
    Route::resource('admin/users', UserManagementController::class)
        ->names('admin.users')
        ->parameters(['users' => 'user'])
        ->except(['show']);
});