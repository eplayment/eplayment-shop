<?php

use Epaygames\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], function () {
    Route::prefix('epaygames')->group(function () {
        Route::get('/success', [PaymentController::class, 'success'])->name('epaygames.success');
        Route::get('/cancel', [PaymentController::class, 'cancel'])->name('epaygames.cancel');
    });
});

Route::post('epaygames/callback', [PaymentController::class, 'callback'])
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
    ->name('epaygames.callback');