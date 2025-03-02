<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserPreferenceController;
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



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/preferences', [UserPreferenceController::class, 'setPreferences']);
    Route::get('/preferences', [UserPreferenceController::class, 'getPreferences']);
    Route::get('/personalized-feed', [UserPreferenceController::class, 'personalizedFeed']);
});
Route::get('/articles', [ArticleController::class, 'index']);  // Get all articles
Route::get('/articles/{id}', [ArticleController::class, 'show']); // Get single article

