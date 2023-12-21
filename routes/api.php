<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\MatrixCompare\MatrixCompareController;
use App\Http\Controllers\Api\VariableInput\VariableInputController;
use App\Http\Controllers\Api\VariableOutput\VariableOutputController;
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

    Route::prefix('/variable-output')->controller(VariableOutputController::class)->group(function () {
        Route::get('/', 'index')->name('variable-output.index');
        Route::get('/{id}', 'show')->name('variable-output.show');
        Route::post('/store', 'store')->name('variable-output.store');
        Route::put('/{id}/update', 'update')->name('variable-output.update');
    });

    Route::prefix('/matrix-compare')->controller(MatrixCompareController::class)->group(function () {
        Route::get('/', 'index')->name('matrix-compare.index');
        Route::get('/{id}', 'show')->name('matrix-compare.show');
        Route::post('/store', 'store')->name('matrix-compare.store');
        Route::put('/{id}/update', 'update')->name('matrix-compare.update');
    });
});
