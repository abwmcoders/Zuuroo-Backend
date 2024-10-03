<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillPayment;
use App\Http\Controllers\DataController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TermConditionController;
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

    //! ---- Home ----
    Route::get('/home', [HomeController::class, 'index']);

    //! ---- KYC ----
    Route::post('kyc/verification', [KycController::class, 'verify_bvn']);

    //! ---- Verify email ----
    Route::get('/email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
    Route::post('/email/resend', [VerificationController::class, 'resend'])->middleware(['throttle:6,1']);

    //! ---- Profile ----
    Route::get('/profile', [UserController::class, 'userProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    //! ---- Airtime Purchase ----
    Route::post('/airtime/purchase', [AirtimeController::class, 'createAirtime']);

    //! ---- Data Purchase ----
    Route::post('/data/purchase', [DataController::class, 'createData']);

    //! ---- Bill Purchase ----
    Route::post('bill/verify-meter', [BillPayment::class, 'verify_meterNo']);
    Route::post('/bill/payment', [BillPayment::class, 'payElectricity']);


    //! ---- Cable Purchase ----
    Route::post('cable/verify-iuc', [BillPayment::class, 'verify_iucNo']);
    Route::get('/cable/cable-plan/{id}', [BillPayment::class, 'getCablePlan']);
    Route::post('/cable/payment', [BillPayment::class, 'payCableTV']);

    //! ---- terms And Condition ----
    Route::prefix('term-conditions')->group(function () {
        Route::get('/', [TermConditionController::class, 'index']);     
        Route::post('/', [TermConditionController::class, 'store']);    
        Route::get('{id}', [TermConditionController::class, 'show']); 
        Route::put('{id}', [TermConditionController::class, 'update']);  
        Route::delete('{id}', [TermConditionController::class, 'destroy']);
    });


    //! ---- Faqs ----
    Route::prefix('faqs')->group(function () {
        Route::get('/', [FaqController::class, 'index']);
        Route::get('{id}', [FaqController::class, 'show']);
        Route::post('/', [FaqController::class, 'store']);   
        Route::put('{id}', [FaqController::class, 'update']); 
        Route::delete('{id}', [FaqController::class, 'destroy']); 
    });


    //! ---- User Activities ----
    Route::get('/faqs', [UserController::class, 'faqs']);
    Route::get('/loans', [UserController::class, 'loans']);
    Route::get('/out-loans', [UserController::class, 'outLoans']);
    Route::get('/user-loan-receipt/{id}', [UserController::class, 'userLoanReceipt']);
    Route::put('/update-password', [UserController::class, 'updatePassword']);
    Route::put('/update-pin', [UserController::class, 'updatePin']);
    Route::put('/update-phone', [UserController::class, 'updatePhoneNumber']);
});


//! ---- Admin Activities ---------------------------------------------------------------------------------------

Route::post('/admin/register', [AdminController::class, 'create']);
Route::post('/admin/login', [AdminController::class, 'login']);

Route::prefix('admin')->group(function () {

    //! Protected routes (requires auth:sanctum middleware)

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('signout', [AdminController::class, 'signout']); 
        Route::get('dashboard', [AdminController::class, 'admin_dashboard']); 
    });
});

