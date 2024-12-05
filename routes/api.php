<?php

use App\Http\Controllers\Api\V1\DevController;
use App\Http\Controllers\Api\V1\LoginController;

use App\Http\Controllers\Api\V1\OtpController;
use App\Http\Controllers\Api\V1\SettingsController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
Route::delete('/user', [UserController::class, 'deleteAccount']);

Route::group(['middleware' => ['auth:sanctum',]], function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/change-password', [UserController::class, 'changePassword']);
    Route::post('/user/edit-profile', [UserController::class, 'editProfile']);
    Route::post('/user/logout', [UserController::class, 'logout']);
});


Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/register', [UserController::class, 'register']);
Route::get('/app/settings', [SettingsController::class, 'show']);
Route::post('/otp/send', [OtpController::class, 'sendOtp']);
Route::post('/otp/verify', [OtpController::class, 'verifyOtp']);
Route::post('/password/reset', [UserController::class, 'resetPassword']);

// DEV ROUTES
Route::get('/role/assign', [DevController::class, 'assignRole']);
Route::get('/role/remove', [DevController::class, 'removeRole']);

Route::any('{url?}/{sub_url?}', function () {
    return response()->json([
        'message' => 'Page Not Found.',
    ], 404);
});
