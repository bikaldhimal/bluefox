<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;
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

// User Section
Route::controller(UserController::class)->group(function () {
    // Signup and Login
    Route::post('/admin/signup', 'createAdmin');
    Route::post('/signup', 'create');
    Route::post('/login', 'login')->name('login');
    // Forgot Password
    Route::post('/password/forgot', 'sendResetLink');
    // after clicking button in mail
    Route::get('/password/forgot/form/{token}', 'resetForm')->name('passwordResetForm');
    Route::post('/password/reset/{token}', 'resetPassword')->name('rPassword');
    Route::get('/resetSuccess', 'resetSuccess')->name('resetSuccess');

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/admin/user', 'index');
        Route::get('/admin/user/{id}', 'show');
        Route::put('/admin/user/{id}/update', 'update');
        Route::delete('/admin/user/{id}/delete', 'destroy');
        Route::get('/profile', 'profile');
        Route::put('/profile/update', 'updateProfile');
        Route::delete('/profile/delete', 'profileDelete');
        Route::post('/admin/deleteAllUser', 'deleteAllUser');
        Route::post('/profile/change-password', 'changepassword');
    });
});

// Setting Section
Route::controller(SettingController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/admin/about/add', [App\Http\Controllers\SettingController::class, 'create']);
        Route::get('/about', [App\Http\Controllers\SettingController::class, 'index']);
        Route::put('/admin/about/{id}/update', [App\Http\Controllers\SettingController::class, 'update']);
    });
});

// Order Section
Route::controller(OrderController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/order', 'create');
        Route::get('/{user_id}/order', 'show');
        Route::get('/list',  'index');
        Route::delete('/order/{id}/delete/}', 'destroy');
        Route::put('/order/{id}', 'update');
    });
});

// OrderItem Section
// Route::controller(OrderItemController::class)->group(function () {
//     Route::group(['middleware' => 'auth:sanctum'], function () {
//         //
//     });
// });

// Feedback Section
Route::controller(FeedbackController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/sendfeedback', [FeedbackController::class, 'create']);
        Route::get('/feedback', [App\Http\Controllers\FeedbackController::class, 'index']);
        Route::put('/feedback/{id}', [App\Http\Controllers\FeedbackController::class, 'update']);
        Route::delete('/feedback/{id}/delete', [App\Http\Controllers\FeedbackController::class, 'destroy']);
    });
});

// Banner Section
Route::controller(BannerController::class)->group(function () {
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/admin/banner/add', 'create');
        Route::get('/banner', 'index');
        Route::put('/admin/banner/{id}/update', 'update');
        Route::delete('/admin/banner/{id}/delete', 'destroy');
    });
});
