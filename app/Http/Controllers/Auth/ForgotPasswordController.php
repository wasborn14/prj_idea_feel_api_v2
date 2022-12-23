<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Log;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        $response == Password::RESET_LINK_SENT;

        return $response
            ? response()->json(['message' => 'パスワード再設定メールを送信しました', 'status' => true], 201)
            : response()->json(['message' => 'パスワード再設定メールを送信できませんでした。', 'status' => false], 401);
    }
}