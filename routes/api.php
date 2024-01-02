<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\MatrixCompare\MatrixCompareController;
use App\Http\Controllers\Api\Result\ResultController;
use App\Http\Controllers\Api\VariableInput\VariableInputController;
use App\Http\Controllers\Api\VariableOutput\VariableOutputController;
use App\Http\Controllers\Role\UserController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/user')->group(function () {
        Route::get('/', function (Request $request) {
            $request->user()->getAllPermissions();
            return $request->user();
        });
    });

    Route::prefix('/variable-input')->controller(VariableInputController::class)->group(function () {
        Route::get('/', 'index')->name('variable-input.index')->middleware(['permission:variable-input.index']);
        Route::get('/{id}', 'show')->name('variable-input.show')->middleware(['permission:variable-input.show']);
        Route::post('/store', 'store')->name('variable-input.store')->middleware(['permission:variable-input.store']);
        Route::put('/{id}/update', 'update')->name('variable-input.update')->middleware(['permission:variable-input.update']);
    });

    Route::prefix('/variable-output')->controller(VariableOutputController::class)->group(function () {
        Route::get('/', 'index')->name('variable-output.index')->middleware(['permission:variable-output.index']);
        Route::get('/{id}', 'show')->name('variable-output.show')->middleware(['permission:variable-output.show']);
        Route::post('/store', 'store')->name('variable-output.store')->middleware(['permission:variable-output.store']);
        Route::put('/{id}/update', 'update')->name('variable-output.update')->middleware(['permission:variable-output.update']);
    });

    Route::prefix('/matrix-compare')->controller(MatrixCompareController::class)->group(function () {
        Route::get('/', 'index')->name('matrix-compare.index')->middleware(['permission:matrix-compare.index']);
        Route::get('/normalization', 'normalization')->name('matrix-compare.normalization')->middleware(['permission:matrix-compare.normalization']);
        Route::get('/weight', 'weight')->name('matrix-compare.weight')->middleware(['permission:matrix-compare.weight']);
        Route::post('/store', 'store')->name('matrix-compare.store')->middleware(['permission:matrix-compare.store']);
        Route::patch('/{id}/update', 'update')->name('matrix-compare.update')->middleware(['matrix-compare.update']);

        Route::put('/edit-by-input-id/{inputId}', 'massUpdateByInputId')->name('matrix-compare.edit-by-input-id');
    });

    Route::post('/predict', [ResultController::class, 'predict'])->name('predict')->middleware(['permission:predict']);
    Route::get('/result-by-user-login', [ResultController::class, 'getByUserLogin'])->name('result-by-user-login');
    Route::get('/result/{id}', [ResultController::class, 'getById'])->name('result.show');
    Route::get('/result-predict/{resultId}', [ResultController::class, 'getPredictByResultId'])->name('result-predict.show');

    // TODO: Result admin
    // Route::get('/result-by-admin', [ResultController::class, 'index'])->name('result')->middleware([]);
});
