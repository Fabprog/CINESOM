<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\TmdbController;
use App\Http\Controllers\SoundtrackController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;

// ── Formulários públicos (sem throttle agressivo) ───────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',                  [LoginController::class, 'showForm'])->name('login');
    Route::get('/register',               [RegisterController::class, 'showForm'])->name('register');
    Route::get('/forgot-password',        [PasswordResetController::class, 'showForgotForm'])->name('password.request');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
});

// ── POST login: throttle exclusivo (5 tentativas/min por IP) ────────────────
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');

// ── Demais submissões de autenticação ────────────────────────────────────────
Route::middleware('throttle:10,1')->group(function () {
    Route::post('/logout',         [LoginController::class, 'logout'])->name('logout');
    Route::post('/register',       [RegisterController::class, 'register']);
    Route::post('/forgot-password',[PasswordResetController::class, 'sendResetLink'])->name('password.email');
    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');
});

// ── Aplicação (requer autenticação) ─────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/', fn() => view('search'));

    Route::middleware('throttle:60,1')->group(function () {
        Route::get('/spotify/search', [SpotifyController::class, 'search']);
        Route::get('/tmdb/search',    [TmdbController::class, 'searchByAlbum']);
        Route::get('/tmdb/providers', [TmdbController::class, 'providers']);
    });

    Route::middleware(['throttle:30,1', 'rapidapi.limiter'])->group(function () {
        Route::post('/soundtrack/search', [SoundtrackController::class, 'search']);
    });
});
