<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

// Not verified user
Route::group([
    'namespace' => 'App\Http\Controllers\Auth',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', 'RegisterController@register'); 
    Route::post('email/resend', 'VerifyEmailController@resend');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::post('password/request', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@resetPassword')->name('password.reset');

    // not verified user redirect
    Route::get('/verified/notice', function () {
        return response()->json('user is not verified', 200);
    })->name('verification.notice');
});

// Verify email
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerifyEmailController::class, '__invoke'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

// Category
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::get('categories', 'CategoryController@getCategoryList');
    Route::put('categories', 'CategoryController@updateCategoryList');
});

// Idea
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::get('ideas/{id}', 'IdeaController@getIdea');
    Route::post('ideas', 'IdeaController@createIdea');
    Route::put('ideas/{id}', 'IdeaController@updateIdea');
    Route::delete('ideas/{id}','IdeaController@deleteIdea');    
});

// Feel_reason
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::get('feel/reasons', 'FeelReasonController@getFeelReasonList');
    Route::get('feel/reasons/select', 'FeelReasonController@getFeelReasonSelectList');
    Route::post('feel/reason', 'FeelReasonController@createFeelReason');
    Route::put('feel/reason/{id}', 'FeelReasonController@updateFeelReason');
    Route::delete('feel/reason/{id}','FeelReasonController@deleteFeelReason');
});

// Feel
Route::group([
    'middleware' => 'verified',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    Route::get('feels/{start_date}/{end_date}', 'FeelController@getFeelList');
    Route::post('feel', 'FeelController@createFeel');
});
