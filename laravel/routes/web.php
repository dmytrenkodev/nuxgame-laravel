<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LuckyController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::prefix('lucky')->group(function () {
    Route::get('{token}', [LuckyController::class, 'show'])->name('lucky.page');
    Route::post('{token}/regenerate', [LuckyController::class, 'regenerate'])->name('lucky.regenerate');
    Route::post('{token}/deactivate', [LuckyController::class, 'deactivate'])->name('lucky.deactivate');
    Route::post('{token}/imfeelinglucky', [LuckyController::class, 'imFeelingLucky'])->name('lucky.imfeelinglucky');
    Route::get('{token}/history', [LuckyController::class, 'history'])->name('lucky.history');
});

