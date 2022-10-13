<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\BobotController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthenticatedSessionController::class, 'create']);
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
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::resource('pelatihan', PelatihanController::class)->except('update');
    Route::post('pelatihan/{pelatihan}/update', [PelatihanController::class, 'update'])->name('pelatihan.update');
    Route::post('pelatihan/{pelatihan}/process', [PelatihanController::class, 'process'])->name('pelatihan.process');

    Route::delete('bahan/{bahan}', [BahanController::class, 'destroy'])->name('bahan.destroy');

    Route::post('/editor/upload', [EditorController::class, 'upload'])->name('editor.upload');
    Route::post('/sipelatihan/editor/upload', [EditorController::class, 'upload']);

    Route::get('pelatihan/{pelatihan}/peserta/export', [PesertaController::class, 'export'])->name('peserta.export');

    Route::resource('katalog', KatalogController::class)->except('update');
    Route::post('katalog/{katalog}/update', [KatalogController::class, 'update'])->name('katalog.update');
    Route::resource('bobot', BobotController::class)->only('store', 'destroy');
    Route::post('katalog/{katalog}/process', [KatalogController::class, 'process'])->name('katalog.process');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
Route::middleware(['auth', 'roles:4'])->group(function () {
    Route::resource('users', UserController::class)->except('update');
    Route::post('users/{user}/update', [UserController::class, 'update'])->name('users.update');
});
