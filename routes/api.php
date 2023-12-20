<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\VariableInput\VariableInputController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::middleware('auth:sanctum')->post('/logout', 'logout');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/variable-input')->controller(VariableInputController::class)->group(function () {
        Route::get('/', 'index')->name('variable-input.index');
        Route::get('/{id}', 'show')->name('variable-input.show');
        Route::post('/store', 'store')->name('variable-input.store');
        Route::put('/{id}/update', 'update')->name('variable-input.update');
    });

    Route::prefix('/variable-output')->controller(VariableInputController::class)->group(function () {
    });
});
