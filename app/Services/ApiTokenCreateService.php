<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class ApiTokenCreateService extends Service
{

    protected $user;
    protected $carbon;
    protected $now;

    public function __construct(User $user, Carbon $carbon = null)
    {
        $this->user = $user;
        $this->carbon = $carbon ?? new Carbon();
        $this->now = $this->carbon->now()->timestamp;
    }

    /**
     * return token and profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken() :object
    {
        return response()->json([
            'token' => [
                'access_token' => $this->createAccessToken(),
                'refresh_token' => $this->createRefreshToken()
            ],
            'profile' => [
                'id' => $this->user->getUserId(),
                'name' => $this->user->getUserName(),
                'email' => $this->user->getUserEmail()
            ]
        ]);
    }

    /**
     * create access_token
     *
     * @return string
     */
    public function createAccessToken() :string
    {
        $customClaims = $this->getJWTCustomClaimsForAccessToken();
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload)->get();

        return $token;
    }

    /**
     * create refresh_token
     *
     * @return string
     */
    public function createRefreshToken() :string
    {
        $customClaims = $this->getJWTCustomClaimsForRefreshToken();
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload)->get();
        return $token;
    }

    /**
     * return CustomClaims for access_token
     *
     * @return object
     */
    public function getJWTCustomClaimsForAccessToken() :object
    {
        $data = [
            'sub' => $this->user->getUserId(),
            'iat' => $this->now,
            'exp' => $this->now + config('jwt.ttl')
        ];

        return JWTFactory::customClaims($data);
    }

    /**
     * return CustomClaims for refresh_token
     *
     * @return object
     */
    public function getJWTCustomClaimsForRefreshToken() :object
    {
        $data = [
            'sub' => $this->user->getUserId(),
            'iat' => $this->now,
            'exp' => $this->now + config('jwt.refresh_ttl')
        ];
        return JWTFactory::customClaims($data);
    }
}