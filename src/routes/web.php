<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemLikeController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

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
Route::post('/guest-view', [VerificationController::class, 'guestView'])->name('guest.view');

Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/mypage', [UserController::class, 'show'])->middleware('auth')->name('mypage');
Route::get('/mypage/profile', [UserController::class, 'edit'])->middleware('auth')->name('profile.edit');
Route::put('/mypage/profile', [UserController::class, 'update'])->middleware('auth')->name('profile.update');

Route::get('/', [ItemController::class, 'index'])->name('item.list');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');
Route::get('/sell', [ItemController::class, 'create'])->middleware('auth')->name('sell');
Route::post('/sell', [ItemController::class, 'store'])->middleware('auth')->name('item.store');

Route::post('/item/{item}/like', [ItemLikeController::class, 'store'])->name('item.like');
Route::delete('/item/{item}/like', [ItemLikeController::class, 'destroy'])->name('item.unlike');

Route::post('/item/{item}/comment', [CommentController::class, 'store'])->middleware('auth')->name('comment.store');
Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->middleware('auth')->name('comment.destroy');

Route::get('/purchase/{item_id}', [PurchaseController::class, 'showPurchaseForm'])->middleware('auth')->name('purchase.form');
Route::post('/purchase/{item_id}/card', [PurchaseController::class, 'cardCheckout'])->middleware('auth')->name('stripe.cardCheckout');
Route::post('/purchase/{item_id}/convenience', [PurchaseController::class, 'convenienceCheckout'])->middleware('auth')->name('stripe.convenienceCheckout');
Route::get('/purchase/{item_id}/{method}/success', [PurchaseController::class, 'success'])->middleware('auth')->name('purchase.success');
Route::get('/purchase/{item_id}/cancel', [PurchaseController::class, 'cancel'])->middleware('auth')->name('purchase.cancel');
Route::post('/webhook/stripe', [PurchaseController::class, 'handleWebhook']);

Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->middleware('auth')->name('address.edit');
Route::patch('/purchase/address', [AddressController::class, 'update'])->middleware('auth')->name('address.update');
