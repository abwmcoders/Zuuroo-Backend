<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

//! ---- LINK FOR PASSWORD RESET ----
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);


Route::middleware('auth:sanctum')->group(function () {
    //! ---- Logout ----
    Route::post('/logout', [AuthController::class, 'logout']);

    //! ---- Verify email ----
    Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend', [VerificationController::class, 'resend'])->middleware(['throttle:6,1']);

    //! ---- Profile ----
    Route::get('/profile', [UserController::class, 'userProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    //! ---- User Activities ----
    Route::get('/faqs', [UserController::class, 'faqs']);
    Route::get('/loans', [UserController::class, 'loans']);
    Route::get('/out-loans', [UserController::class, 'outLoans']);
    Route::get('/user-loan-receipt/{id}', [UserController::class, 'userLoanReceipt']);
    Route::put('/update-password', [UserController::class, 'updatePassword']);
    Route::put('/update-phone', [UserController::class, 'updatePhoneNumber']);
});

