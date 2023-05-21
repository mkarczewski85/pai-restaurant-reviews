<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\auth\AuthController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/businesses', [BusinessController::class, 'index']);
    Route::get('/businesses/{business}', [BusinessController::class, 'getBusinessDetails']);
    Route::post('/businesses', [BusinessController::class, 'store']);
    Route::put('/businesses/{business}', [BusinessController::class, 'update']);
    Route::delete('/businesses/{business}', [BusinessController::class, 'delete']);
    Route::get('/businesses/{businessId}/reviews', [ReviewController::class, 'getBusinessReviews']);
    Route::get('/businesses/{businessId}/my-review', [ReviewController::class, 'getMyReview']);
    Route::delete('/my/reviews/{reviewId}', [ReviewController::class, 'deleteMyReview']);
});
