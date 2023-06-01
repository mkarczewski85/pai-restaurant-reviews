<?php

use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ReviewLikesController;
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

    Route::post('/favorites/{businessId}', [FavouriteController::class, 'addToFavorites']);
    Route::delete('/favorites/{businessId}', [FavouriteController::class, 'removeFromFavorites']);
    Route::get('/favorites', [FavouriteController::class, 'getFavorites']);

    Route::get('/businesses/{businessId}/reviews', [ReviewController::class, 'getBusinessReviews']);

    Route::post('/review/{reviewId}/like', [ReviewLikesController::class, 'likeReview']);
    Route::delete('/review/{reviewId}/like', [ReviewLikesController::class, 'unlikeReview']);

    Route::get('/businesses/{businessId}/my-review', [ReviewController::class, 'getMyReview']);
    Route::post('/businesses/{businessId}/my-review', [ReviewController::class, 'storeMyReview']);
    Route::delete('/businesses/{businessId}/my-review', [ReviewController::class, 'deleteMyReview']);
});
