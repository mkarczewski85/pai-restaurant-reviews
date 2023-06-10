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

    // admin's endpoints
    Route::post('/businesses', [BusinessController::class, 'store'])->middleware('restrictRole:admin');
    Route::put('/businesses/{business}', [BusinessController::class, 'update'])->middleware('restrictRole:admin');
    Route::delete('/businesses/{business}', [BusinessController::class, 'delete'])->middleware('restrictRole:admin');

    // user's endpoints
    Route::get('/user-details', [AuthController::class, 'userDetails'])->middleware('restrictRole:user');
    Route::put('/user-password', [AuthController::class, 'changePassword'])->middleware('restrictRole:user');

    Route::post('/favorites/{businessId}', [FavouriteController::class, 'addToFavorites'])->middleware('restrictRole:user');
    Route::delete('/favorites/{businessId}', [FavouriteController::class, 'removeFromFavorites'])->middleware('restrictRole:user');
    Route::get('/favorites', [FavouriteController::class, 'getFavorites'])->middleware('restrictRole:user');

    Route::get('/businesses/{businessId}/reviews', [ReviewController::class, 'getBusinessReviews'])->middleware('restrictRole:user');

    Route::post('/review/{reviewId}/like', [ReviewLikesController::class, 'likeReview'])->middleware('restrictRole:user');
    Route::delete('/review/{reviewId}/like', [ReviewLikesController::class, 'unlikeReview'])->middleware('restrictRole:user');

    Route::get('/businesses/{businessId}/my-review', [ReviewController::class, 'getMyReview'])->middleware('restrictRole:user');
    Route::post('/businesses/{businessId}/my-review', [ReviewController::class, 'storeMyReview'])->middleware('restrictRole:user');
    Route::delete('/businesses/{businessId}/my-review', [ReviewController::class, 'deleteMyReview'])->middleware('restrictRole:user');
});
