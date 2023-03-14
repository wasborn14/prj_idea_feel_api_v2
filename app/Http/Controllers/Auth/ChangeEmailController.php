<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\EmailReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\User;

class ChangeEmailController extends Controller
{
    public function sendChangeEmailLink(Request $request)
    {
        $new_email = $request->new_email;

        $token = hash_hmac(
            'sha256',
            Str::random(40) . $new_email,
            config('app.key')
        );

        DB::beginTransaction();
        try {
            $param = [];
            $param['user_id'] = Auth::id();
            $param['new_email'] = $request->new_email;
            $param['token'] = $token;

            $email_reset = EmailReset::create($param);

            DB::commit();

            $email_reset->sendEmailResetNotification($token);

            return response()->json(['message' => 'Successfully Send Change Email Url']);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw new InternalServerErrorException(
                'Failed Send Change Email Url',
                500,
                'Internal Server Error'
            );
        }
    }

    /**
     * Change Email Address
     *
     * @param Request $request
     * @param [type] $token
     */
    public function reset(Request $request, $token)
    {
        $email_resets = DB::table('email_resets')
            ->where('token', $token)
            ->first();

        if ($email_resets && !$this->tokenExpired($email_resets->created_at)) {

            $user = User::find($email_resets->user_id);
            $user->email = $email_resets->new_email;
            $user->save();

            // delete record
            DB::table('email_resets')
                ->where('token', $token)
                ->delete();

            return redirect(config('app.front_url') . "/auth/completeEmailReset");
        } else {
            if ($email_resets) {
                DB::table('email_resets')
                    ->where('token', $token)
                    ->delete();
            }
            return redirect(config('app.front_url') . "/auth/failedEmailReset");
        }
    }


    /**
     * Check Token Expired
     *
     * @param  string  $createdAt
     * @return bool
     */
    protected function tokenExpired($createdAt)
    {
        // setting 60 minutes
        $expires = 60 * 60;
        return Carbon::parse($createdAt)->addSeconds($expires)->isPast();
    }
}
