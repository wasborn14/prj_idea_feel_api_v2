<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Services\ApiTokenCreateService;
use App\Models\User;
use App\Models\Category;
use App\Models\Tab;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $isProvider = $request->has('provider');
        if ($isProvider) {

            // OAuth User Login or Register
            
            $existUser = User::where('email', $request->email)->first();
            if ($existUser) {

                $credentials = [
                    'email' => $request->input('email'),
                    'password' => $request->email . config('auth.oauth_password_secret')
                ];

                if (! $token = auth()->attempt($credentials)) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                $user = Auth::user();
                $ApiTokenCreateService = new ApiTokenCreateService($user);
        
                return $ApiTokenCreateService->respondWithToken(); 
            } else {
                $this->registerOAuth($request);
            }

        } else {
            
            // Email User Login

            $credentials = request(['email', 'password']);

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            $user = Auth::user();
            if (!$user->hasVerifiedEmail()){
                return response()->json(['error' => 'Not Verified'], 401); 
            }
            $ApiTokenCreateService = new ApiTokenCreateService($user);
    
            return $ApiTokenCreateService->respondWithToken(); 
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * User OAuth Registration
     *
     * @param Request $request
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function registerOAuth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'provider' => 'required'
        ], ['email.unique' => 'email is already taken']);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        try {
            DB::beginTransaction();

            // Create User
            $user = new User;
            $user->fill([
                'name' =>  $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->email . config('auth.oauth_password_secret')),
                'provider' => $request->provider,
            ]);
            $user->save();

            // Make email verified when use provider
            if ($user->markEmailAsVerified()) {
                event(new Verified($user));
            }
    
            // Create Empty Category List
            $category = new Category;
            $category->user_id = $user->id;
            $category->category_list = [];
            $category->save();

            // Create Empty Tab List
            $tab = new Tab;
            $tab->user_id = $user->id;
            $tab->tab_list = [];
            $tab->save();

            DB::commit();            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            throw new InternalServerErrorException(
                'User registration failed',
                500,
                'Internal Server Error'
            );
        }

        $ApiTokenCreateService = new ApiTokenCreateService($user);
    
        return $ApiTokenCreateService->respondWithToken();

    }

}