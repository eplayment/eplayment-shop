<?php
use Illuminate\Support\Facades\Route;
use Epaygames\Http\Controllers\Api\CustomerController;
use Epaygames\Http\Controllers\Api\WishlistController;

Route::group([
    'prefix'     => 'api/v1',
    'middleware' => ['sanctum.locale', 'sanctum.currency'],
], function () {
    Route::post('customer/reset-password', [CustomerController::class, 'store']);

    Route::group(['middleware' => ['auth:sanctum', 'sanctum.customer']], function () {
        Route::post('customer/wishlist/{id}/alt-move-to-cart', [WishlistController::class, 'moveToCart']);
    });
});
