<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\ApiTokenCreateService;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class RefreshTokenController extends Controller
{
    private $user;
    private $user_id;
    private $access_token;

    /**
     * refresh access_token
     *
     * @Header - Authorization: Bearer refresh_token
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        $this->access_token = auth()->refresh();
        $this->user_id = (auth()->user()->id);
        $this->user = User::where('id', $this->user_id)->first();

        return $this->respondWithToken();
    }

    /**
     * return token and profile
     *
     * @return JsonResponse
     */
    public function respondWithToken(): JsonResponse
    {
        return response()->json([
            'token' => [
                'access_token' => $this->access_token,
            ],
            'profile' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ]
        ]);
    }
}
