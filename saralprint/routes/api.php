<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/signup', [App\Http\Controllers\UserController::class, 'create']);
Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);

// Forger Password api
Route::post('/password/forgot', [App\Http\Controllers\UserController::class, 'sendResetLink']);
Route::post('/password/reset', [App\Http\Controllers\UserController::class, 'resetPassword']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    // User Section
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index']);
    Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'show']);
    Route::put('/user/{id}/update', [App\Http\Controllers\UserController::class, 'update']);
    Route::put('/profile/update', [App\Http\Controllers\UserController::class, 'updateProfile']);
    Route::delete('/user/{id}/delete', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile']);
    Route::delete('/profile/delete', [App\Http\Controllers\UserController::class, 'profileDelete']);
    Route::post('/profile/change-password', [App\Http\Controllers\UserController::class, 'changepassword']);

    // Setting Section
    Route::get('/about', [App\Http\Controllers\SettingController::class, 'create']);
    Route::get('/about/show', [App\Http\Controllers\SettingController::class, 'index']);
    Route::put('/about/{id}/update', [App\Http\Controllers\SettingController::class, 'update']);

    // Feedback Section
    Route::get('/sendfeedback', [FeedbackController::class, 'create']);
    Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index']);
    Route::put('/feedback/{id}', [App\Http\Controllers\FeedbackController::class, 'update']);
    Route::delete('/feedback/{id}/delete', [App\Http\Controllers\FeedbackController::class, 'destroy']);
});
