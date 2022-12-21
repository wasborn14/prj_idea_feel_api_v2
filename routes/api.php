<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use \Symfony\Component\HttpFoundation\Response;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers\Auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

// 認証済みのユーザーがアクセスできる範囲
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers\Verified',
    'prefix' => 'verified'
], function ($router) {
    Route::get('test', 'VerifiedTestController@test');
});

// メール認証要求ページに飛ばす
Route::get('/verified/notice', function () {
    return response()->json('user is not verified', Response::HTTP_OK);
})->name('verification.notice');

// ユーザー登録
Route::group([
    'middleware' => 'guest',
    'namespace' => 'App\Http\Controllers\Auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', 'RegisterController@register');
});

// メール再送信
Route::group([
    'middleware' => 'guest',
    'namespace' => 'App\Http\Controllers\Auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('email/resend', 'VerifyEmailController@resend');
});

// メールアドレス認証
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');