<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::name('api.')->group(function () {
    Route::get('/currencies', [Api\CurrencyController::class, 'index'])->name('currency.list');
    Route::get('/currency/{id}', [Api\CurrencyController::class, 'show'])->name('currency.show');
});


