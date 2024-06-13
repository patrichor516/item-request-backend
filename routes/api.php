<?php

use App\Http\Controllers\API\ItemRequestApiController;
use App\Http\Controllers\API\ApprovalController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\AuthController;
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

Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);

    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('approvals', [ApprovalController::class, 'index']);
        Route::post('approvals/{id}/approve', [ApprovalController::class, 'approve']);
        Route::get('list/request', [ItemRequestApiController::class, 'index']);
        Route::put('update/request/{id}', [ItemRequestApiController::class, 'update']);
        Route::delete('delete/request/{id}', [ItemRequestApiController::class, 'destroy']);
    });
});

Route::group(['middleware' => 'auth:api'], function() {
    Route::post('create/request', [ItemRequestApiController::class, 'store']);
});

Route::get('user/list', [UserController::class, 'index']);