<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use \Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request)
    {
        /** @var Illuminate\Validation\Validator $validator */
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            DB::beginTransaction();

            $user = new User;
            $user->fill([
                'name' =>  $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            event(new Registered($user));
    
            $category = new Category;
            $category->user_id = $user->id;
            $category->category_list = [];
            $category->save();

            DB::commit();
            
            return response()->json('User registration completed', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return response()->json([
                "message" => "User registration failed"
            ], 500);
        }

    }

}