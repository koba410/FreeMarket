<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\VerificationController;

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

// メールアドレス認証メールの認証ボタン押下後、反映させるために必要なルート
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');
//メール認証通知ページ用のビュー
Route::get('/email/verify', [VerificationController::class, 'show'])
    ->middleware('auth')->name('verification.notice');
// 認証メール再送信
Route::post('/email/verification-notification', [VerificationController::class, 'send'])
    ->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/mypage', [UserController::class, 'show'])->name('mypage');
Route::get('/mypage/profile', [UserController::class, 'edit'])->name('profile.edit');
Route::put('/mypage/profile', [UserController::class, 'update'])->name('profile.update');

Route::get('/', [ItemController::class, 'index'])->name('item.list');
Route::get('/sell', [ItemController::class, 'create'])->name('sell');
Route::post('/sell', [ItemController::class, 'store'])->name('item.store');
