<?php

use App\Http\Controllers\AboutUsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AirtimeController;
use App\Http\Controllers\AirtimeProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillPayment;
use App\Http\Controllers\CablePlanController;
use App\Http\Controllers\CableSubscriptionController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\ElectricityBillerNameController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TermConditionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/request-password-reset', [AuthController::class, 'requestPasswordReset']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

//! ---- LINK FOR PASSWORD RESET ----
Route::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/password/reset', [PasswordResetController::class, 'reset']);

//! ---- Countries ----
Route::get('/countries', [CountryController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    //! ---- Logout ----
    Route::post('/logout', [AuthController::class, 'logout']);

    //! ---- Home ----
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/country/isloan', [CountryController::class, 'isloan']);
    Route::get('/country/country-by-phonecode/{id}', [CountryController::class, 'phoneCode']);
    Route::get('/country/country-by-status', [CountryController::class, 'CountryByStatus']);

    Route::get('/operators', [OperatorController::class, 'index']);
    Route::get('/operators/{id}', [OperatorController::class, 'show']);
    Route::get('/operator/by-country/{id}', [OperatorController::class, 'operatorsByCountry']);

    Route::get('/product/categories', [ProductCategoryController::class, 'index']);
    Route::get('/product/category/{id}', [ProductCategoryController::class, 'show']);
    Route::get('/product/category-status', [ProductCategoryController::class, 'ProductCategoryStatus']);
    Route::get('/product/categories-byoperator/{id}', [ProductCategoryController::class, 'ProductCategoryByOperator']);

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
    Route::get('/airtime/product-category/{id}', [AirtimeProductController::class, 'AirtimeProductByCategory']);
    Route::get('/airtime/product-operator/{id}', [AirtimeProductController::class, 'AirtimeProductByOperator']);

    //! ---- Data Purchase ----
    Route::post('/data/purchase', [DataController::class, 'createData']);
    Route::get('/data/product-operator/{id}', [ProductController::class, 'ProductByOperator']);
    Route::get('/data/product-category/{id}', [ProductController::class, 'ProductByCategory']);
    Route::get('/data/product-by-phone/{id}', [ProductController::class, 'getProductByPhone']);

    //! ---- Bill Purchase ----
    Route::post('/bill/verify-meter', [BillPayment::class, 'verify_meterNo']);
    Route::post('/bill/payment', [BillPayment::class, 'payElectricity']);
    Route::get('/bill/electricity-billers', [ElectricityBillerNameController::class, 'getAll']);
    Route::get('/bill/electricity-billers/status/{status}', [ElectricityBillerNameController::class, 'getByStatus']);


    //! ---- Cable Purchase ----
    Route::post('/cable/verify-iuc', [BillPayment::class, 'verify_iucNo']);
    Route::get('/cable/cable-plan/{id}', [BillPayment::class, 'getCablePlan']);
    Route::post('/cable/payment', [BillPayment::class, 'payCableTV']);

    //!------------------ cable providers--------------
    Route::get('/cable-subscriptions', [CableSubscriptionController::class, 'index']);
    Route::post('/cable-subscriptions', [CableSubscriptionController::class, 'store']);
    Route::get('/cable-subscriptions/{id}', [CableSubscriptionController::class, 'show']);
    Route::put('/cable-subscriptions/{id}', [CableSubscriptionController::class, 'update']);
    Route::delete('/cable-subscriptions/{id}', [CableSubscriptionController::class, 'destroy']);
    Route::get('/cable-subscriptions/status/{status}', [CableSubscriptionController::class, 'getByStatus']);

    //!---------------cable plans -------------------
    Route::get('/cable-plans', [CablePlanController::class, 'index']);
    Route::post('/cable-plans', [CablePlanController::class, 'store']);
    Route::get('/cable-plans/{id}', [CablePlanController::class, 'show']);
    Route::put('/cable-plans/{id}', [CablePlanController::class, 'update']);
    Route::delete('/cable-plans/{id}', [CablePlanController::class, 'destroy']);
    Route::get('/cable-plans/provider/{provider_code}', [CablePlanController::class, 'getByProviderCode']);

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


    //! ---- About us ---------
    Route::get('about-us', [AboutUsController::class, 'index']);
    Route::post('about-us', [AboutUsController::class, 'store']);
    Route::put('about-us/{id}', [AboutUsController::class, 'update']);
    Route::delete('about-us/{id}', [AboutUsController::class, 'destroy']);

    //! ---- History ----
    Route::controller(HistoryController::class)->group(function () {
        Route::get('histories', 'index');
        Route::get('histories/{id}', 'show');
        Route::post('histories', 'store');
        Route::put('histories/{id}', 'update');
        Route::delete('histories/{id}', 'destroy');

        Route::get('histories/data', 'getDataHistories');
        Route::get('histories/airtime', 'getAirtimeHistories');
        Route::get('user/histories', 'getUserHistory');
        Route::get('histories/{purchase}', 'getUserPurchaseHistory');
        Route::get('histories/{status}', 'getUserProcessingStateHistory'); 
    });


    //! ---- User Activities ----
    Route::get('/user/faqs', [UserController::class, 'faqs']);
    Route::get('/user/loans', [UserController::class, 'loans']);
    Route::get('/user/out-loans', [UserController::class, 'outLoans']);
    Route::get('/user-loan-receipt/{id}', [UserController::class, 'userLoanReceipt']);
    Route::put('/user/update-password', [UserController::class, 'updatePassword']);
    Route::put('/user/update-pin', [UserController::class, 'updatePin']);
    Route::put('/update-phone', [UserController::class, 'updatePhoneNumber']);
    Route::post('/user/verify-pin', [UserController::class, 'verifyPin']);
});


//! ---- Admin Activities ---------------------------------------------------------------------------------------

Route::post('/admin/register', [AdminController::class, 'create']);
Route::post('/admin/login', [AdminController::class, 'login']);

Route::prefix('admin')->group(function () {

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('signout', [AdminController::class, 'signout']);
        Route::get('dashboard', [AdminController::class, 'admin_dashboard']);
    });
});

