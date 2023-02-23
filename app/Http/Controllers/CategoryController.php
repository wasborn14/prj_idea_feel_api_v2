<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    //
    public function createCategory(Request $request) {
        $user_id = Auth::id();
        $category = new Category;
        $category->user_id = $user_id;
        $category->category_list = $request->category_list;
        $category->save();
  
        return response()->json([
           "message" => "category record created"
        ], 201);
    }

    public function getCategory() {
        $user_id = Auth::id(); 
        $category_list = Category::where('user_id', $user_id)->first()->category_list;

        return response($category_list, 200);
    }

    public function updateCategory(Request $request) {

        try {
            $user_id = Auth::id(); 
            $category = Category::where('user_id', $user_id)->first();
            $category->category_list = is_null($request->category_list) ? $category->category_list : $request->category_list;
            $category->save(); 
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "records updated failed"
            ], 200);
        }
        
    }
}
