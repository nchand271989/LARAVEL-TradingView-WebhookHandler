<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


use App\Http\Controllers\Api\WebhookValidationController;

Route::post('/{secretkey}/{userid}/{webhookid}/{strategyid}/{exchangeid}/{currencyid}/{hash}', [WebhookValidationController::class, 'validateUrl']);

