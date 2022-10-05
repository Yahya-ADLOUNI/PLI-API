<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SourceController;
use App\Http\Controllers\Api\ArtworkController;
use App\Http\Controllers\Api\InterestController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::put('users/{user}/{artwork}/add-user-artwork', [UserController::class, 'putUserArtworks']);
    Route::put('users/{user}/{interest}/add-user-interest', [UserController::class, 'putUserInterests']);
    Route::get('users/{user}/get-user-interests', [UserController::class, 'getUserInterests']);
    Route::get('users/{user}/get-user-artworks', [UserController::class, 'getUserArtworks']);

    Route::apiResource('sources', SourceController::class);
    Route::get('sources/{source}/get-source-artworks', [SourceController::class, 'getSourceArtworks']);

    Route::apiResource('artworks', ArtworkController::class);
    Route::get('artworks/{artwork}/get-artwork-interests', [ArtworkController::class, 'getArtworkInterests']);
    Route::get('artworks/{artwork}/get-artwork-users', [ArtworkController::class, 'getArtworkUsers']);

    Route::apiResource('interests', InterestController::class);
});
