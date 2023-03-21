<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * User Registration
     *
     * @param Request $request
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

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
                'password' => Hash::make($request->password),
            ]);
            $user->save();
            event(new Registered($user));
    
            // Create Empty Category List
            $category = new Category;
            $category->user_id = $user->id;
            $category->category_list = [];
            $category->save();

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
        
        return response()->json([
            "message" => "User registration completed"
        ], 200);
    }

}