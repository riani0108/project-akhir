<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\HitungController;



Route::resource('/', App\Http\Controllers\HomeController::class)->name('index', 'home');
Route::get('/home', [HomeController::class, 'index'])->name('home.index');
Route::resource('/about', App\Http\Controllers\AboutController::class)->name('index', 'about');
Route::resource('/hitung', App\Http\Controllers\HitungController::class)->name('index', 'hitung');
Route::resource('/admin', App\Http\Controllers\AdminController::class);
Route::resource('/data-tower', App\Http\Controllers\DataTowerController::class);
Route::get('/data/tower', [App\Http\Controllers\DataTowerController::class, 'all']);
Route::resource('/data-antenna', App\Http\Controllers\DataAntennaController::class);


