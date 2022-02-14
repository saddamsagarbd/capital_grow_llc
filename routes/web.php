<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JoiningController;
use App\Http\Controllers\ReferenceController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('website.index');
});

Auth::routes();

Route::get('/user-login', [RegisterController::class, 'index']);

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index']);
Route::get('/confirm-payment', [HomeController::class, 'confirmPayment']);
Route::post('/make-payment-confirm', [HomeController::class, 'makePaymentConfirm'])->name('make-payment-confirm');
Route::get('/active-users', [HomeController::class, 'getActiveUsers']);
Route::get('/pending-users', [HomeController::class, 'getPendingUsers']);
Route::get('/modify-user/{id}', [HomeController::class, 'getUserById']);
Route::post('/update-user-info', [HomeController::class, 'UpdateRegisteredUserInfo'])->name('update-user-info');
Route::post('/confirm-payment', [HomeController::class, 'confirmPaymentRequest']);

// JoiningController
Route::get('/joining', [JoiningController::class, 'index']);
Route::post('/get-referral-name', [JoiningController::class, 'getRefDetails']);
Route::post('/make-user-joining', [JoiningController::class, 'makeUserJoining'])->name('make-user-joining');
Route::get('/weekly-bonus', [JoiningController::class, 'weeklyBonusShared']);
Route::get('/test', [JoiningController::class, 'test']);

// Referal user
Route::get('/refered-users', [ReferenceController::class, 'index']);
Route::post('/get-refered-users', [ReferenceController::class, 'getReferedUser']);
Route::get('/earning-statement', [ReferenceController::class, 'getIncomeStatement']);

// manage accounts
Route::get('/manage-accounts', [HomeController::class, 'manageAccounts']);
Route::get('/add-account-info', [HomeController::class, 'addAccountInfoFrom']);
Route::post('/add-account-info', [HomeController::class, 'saveAccountInfo'])->name('save-account-info');

//balance withdrawal
Route::get('/balance-withdrawal', [JoiningController::class, 'balanceWithdrawalForm']);
Route::post('/send-otp', [JoiningController::class, 'sendOtpMail']);
Route::post('/withdrawal-balance', [JoiningController::class, 'balanceWithdrawalRequest'])->name('withdrawal-balance');
Route::get('/withdrawal-request', [JoiningController::class, 'getWithdrawalRequest']);
Route::get('/withdrawal-requests', [JoiningController::class, 'getAllWithdrawalRequests']);
Route::post('/update-withdrawal-request', [JoiningController::class, 'updateWithdrawalRequest'])->name('update-withdrawal-request');