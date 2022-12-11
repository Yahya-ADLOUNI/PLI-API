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
Route::post('signup', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);

    Route::apiResource('sources', SourceController::class);

    Route::apiResource('artworks', ArtworkController::class);
    Route::get('art/spotify', [ArtworkController::class, 'getSpotify']);
    Route::get('art/imdb', [ArtworkController::class, 'getIMDB']);
    Route::get('feed', [ArtworkController::class, 'feed']);

});
