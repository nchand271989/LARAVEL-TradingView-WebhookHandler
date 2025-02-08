<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


use App\Http\Controllers\CurrencyController;

Route::middleware(['auth'])->group(function () {
    Route::resource('/markets-assets/currencies', CurrencyController::class);
    Route::patch('/markets-assets/currencies/{curid}/toggle-status', [CurrencyController::class, 'toggleStatus'])
    ->name('currencies.toggleStatus');
});



use App\Http\Controllers\TermsController;

Route::get('/terms', [TermsController::class, 'show'])->name('terms');

use App\Http\Controllers\PrivacyPolicyController;

Route::get('/policy', [PrivacyPolicyController::class, 'show'])->name('policy');
