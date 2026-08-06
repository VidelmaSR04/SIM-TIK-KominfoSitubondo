<?php

use Illuminate\Support\Facades\Route;
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Halaman Login
Route::get('/login', function () {
    return view('login');
})->name('login');

// Halaman Register
Route::get('/register', function () {
    return view('register');
})->name('register');

// Halaman Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Halaman detail
Route::get('/detailserver', function () {
    return view('detailserver');
})->name('detailserver');

// Halaman Input Data Perangkat
Route::get('/inputdata', function () {
    return view('inputdata');
})->name('inputdata');
