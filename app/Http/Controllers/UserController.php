<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exceptions\ForbiddenException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Update User Name
     *
     * @return JsonResponse
     */
    public function updateUserName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->save();
        
        return response()->json([
            "message" => "User name updated"
        ], 200);
    }

    /**
     * Update User Password
     *
     * @return JsonResponse
     */
    public function updateUserPassword(Request $request)
    {
        throw new ForbiddenException(
            'Not Authorized',
            403,
            'Passwords do not match'
        );

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            're_new_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $user = Auth::user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
        } else {
            throw new ForbiddenException(
                'Not Authorized',
                403,
                'Passwords do not match'
            );
            // return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        return response()->json([
            "message" => "User password updated"
        ], 200);
    }
}
