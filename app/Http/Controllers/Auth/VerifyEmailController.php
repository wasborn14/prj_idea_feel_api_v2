<?php
namespace App\Http\Controllers\Auth;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{

    public function __invoke(Request $request): RedirectResponse
    {
        $user = User::find($request->route('id'));

        if ($user->hasVerifiedEmail()) {
            return redirect(config('app.front_url') . "/auth/alreadyActivated");
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect(config('app.front_url') . "/auth/activated");
    }

    public function resend(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        $user->sendEmailVerificationNotification();

        return response()->json('email resend', Response::HTTP_OK);
    }
}