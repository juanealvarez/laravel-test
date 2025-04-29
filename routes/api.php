<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\SuperWalletzWebhookController;
use Illuminate\Support\Facades\Route;

// Rutas para iniciar pagos
Route::post('/pay/easymoney', [PaymentController::class, 'payEasyMoney']);
Route::post('/pay/superwalletz', [PaymentController::class, 'paySuperWalletz']);

// Ruta para recibir el webhook de SuperWalletz
Route::post('/webhook/superwalletz', [WebhookController::class, 'handler']);
