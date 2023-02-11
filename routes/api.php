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

// 認証前のユーザーがアクセスできる範囲
Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', 'RegisterController@register'); 
    Route::post('email/resend', 'VerifyEmailController@resend');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('password/request', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@resetPassword')->name('password.reset');

    // 認証済みのルートに未承認ユーザーがアクセスした際にこちらにリダイレクトする
    Route::get('/verified/notice', function () {
        return response()->json('user is not verified', Response::HTTP_OK);
    })->name('verification.notice');
});

// 認証済みのユーザーがアクセスできる範囲
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers\Verified',
    'prefix' => 'verified'
], function ($router) {
    Route::get('test', 'VerifiedTestController@test');
});

// カテゴリ
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    // Route::get('categories', 'CategoryController@getAllCategories');
    Route::get('categories', 'CategoryController@getCategory');
    Route::post('categories', 'CategoryController@createCategory');
    Route::put('categories', 'CategoryController@updateCategory');
    // Route::delete('categories/{id}','CategoryController@deleteCategory');    
});

// アイデア
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    // Route::get('ideas', 'IdeaController@getAllIdeas');
    Route::get('ideas/{id}', 'IdeaController@getIdea');
    Route::post('ideas', 'IdeaController@createIdea');
    Route::put('ideas/{id}', 'IdeaController@updateIdea');
    Route::delete('ideas/{id}','IdeaController@deleteIdea');    
});

// カテゴリ
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    // Route::get('feels', 'FeelController@getAllFeels');
    Route::get('feels/{start_date}/{end_date}', 'FeelController@getFeelList');
    // Route::post('feels', 'FeelController@createFeel');
    Route::post('feel', 'FeelController@createFeel');
    // Route::put('feels', 'FeelController@updateFeel');
    // Route::delete('feels/{id}','FeelController@deleteFeel');    
});

// メールアドレスの認証
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');