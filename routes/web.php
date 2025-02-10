<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\PrivacyPolicyController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\StrategyController;
use App\Http\Controllers\WebhookController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/terms', [TermsController::class, 'show'])->name('terms');

Route::get('/policy', [PrivacyPolicyController::class, 'show'])->name('policy');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {

    // Email Verification
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $user = \App\Models\User::where('id', $request->route('id'))->firstOrFail();
    
        if (!hash_equals((string) $request->route('hash'), sha1($user->email))) {
            abort(403);
        }
    
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
    
        return redirect('/dashboard')->with('verified', true);
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    // Market Assets
    Route::get('/markets-assets', function () {
        return view('markets-assets');
    })->name('markets.assets');

    // Currencies
    Route::resource('/markets-assets/currencies', CurrencyController::class)->except(['destroy']);
    Route::patch('/markets-assets/currencies/{curid}/toggle-status', [CurrencyController::class, 'toggleStatus'])
    ->name('currencies.toggleStatus');

    // Exchanges
    Route::resource('/markets-assets/exchanges', ExchangeController::class)->except(['destroy']);
    Route::patch('/markets-assets/exchanges/{exchange}/toggle-status', [ExchangeController::class, 'toggleStatus'])
    ->name('exchanges.toggleStatus');


    // Strategies
    Route::middleware(['auth'])->group(function () {
        Route::resource('/strategies', StrategyController::class)->except(['destroy']);
        Route::patch('/strategies/{strategy}/toggle-status', [StrategyController::class, 'toggleStatus'])->name('strategies.toggleStatus');
    });

    // Webhooks
    Route::resource('webhooks', WebhookController::class);
    Route::patch('/webhooks/{webhook}/toggle-status', [WebhookController::class, 'toggleStatus'])->name('webhooks.toggleStatus');


});
