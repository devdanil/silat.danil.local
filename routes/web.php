<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::resource('pelatihan', PelatihanController::class)->except('update');
    Route::post('pelatihan/{pelatihan}/update', [PelatihanController::class, 'update'])->name('pelatihan.update');
    Route::post('pelatihan/{pelatihan}/process', [PelatihanController::class, 'process'])->name('pelatihan.process');

    Route::delete('bahan/{bahan}', [BahanController::class, 'destroy'])->name('bahan.destroy');

    Route::post('editor/upload', [EditorController::class, 'upload'])->name('editor.upload');

    Route::post('bobot/{pelatihan}', [BobotController::class, 'store'])->name('bobot.store');


    Route::resource('pendaftaran', PelatihanController::class);
    Route::resource('menus', PelatihanController::class);
    Route::resource('roles', PelatihanController::class);
    Route::resource('aplikasi', PelatihanController::class);
    Route::resource('roles-menus', PelatihanController::class);
    Route::resource('users-roles', PelatihanController::class);
    Route::resource('users-roles', PelatihanController::class);
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
Route::middleware(['auth', 'roles:1'])->group(function () {
    Route::resource('users', UserController::class)->except('update');
    Route::post('users/{user}/update', [UserController::class, 'update'])->name('users.update');
});
