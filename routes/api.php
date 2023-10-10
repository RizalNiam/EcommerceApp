<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\SendEmailController;


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

Route::post('/auth/login', [UserController::class, 'login']);
Route::post('/auth/register', [UserController::class, 'register']);

Route::middleware('jwt.verify')->group(function () {
    Route::post('auth/logout', [UserController::class, 'logout']);
    Route::get('auth/getprofile', [UserController::class, 'getprofile']);
    Route::post('auth/editprofile', [UserController::class, 'editprofile']);
    Route::get('auth/refresh', [UserController::class, 'refresh']);
    Route::post('auth/addbook', [BookController::class, 'addbook']);
    Route::get('auth/children_destinations', [BookController::class, 'get_children_destinations']);
    Route::get('auth/nature_destinations', [BookController::class, 'get_nature_destinations']);
    Route::get('auth/get_books', [BookController::class, 'get_books']);
    Route::get('auth/get_bookmarks', [UserController::class, 'get_bookmarks']);
    Route::get('auth/img_slider', [SliderController::class, 'get_img_slider']);
    Route::post('auth/add_sliders', [SliderController::class, 'add_sliders']);
    Route::post('auth/addreview', [ReviewController::class, 'addreview']);
    Route::post('auth/addcart', [CartController::class, 'addcart']);
    Route::get('auth/getcarts', [CartController::class, 'get_carts']);
    Route::post('auth/set_favorite', [FavoriteController::class, 'set_favorite']);
    Route::post('auth/unset_favorite', [FavoriteController::class, 'unset_favorite']);
    Route::get('auth/get_favorites', [FavoriteController::class, 'get_favorites']);
    Route::get('send-email', [SendEmailController::class, 'index']);
});
