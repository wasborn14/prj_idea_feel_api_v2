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
        $category->context = $request->context;
        $category->save();
  
        return response()->json([
           "message" => "category record created"
        ], 201);
    }

    public function getCategory() {
        $user_id = Auth::id(); 
        $category_context = Category::where('user_id', $user_id)->first()->context;

        return response($category_context, 200);
    }

    public function updateCategory(Request $request) {

        try {
            $user_id = Auth::id(); 
            $category = Category::where('user_id', $user_id)->first();
            $category->context = is_null($request->context) ? $category->context : $request->context;
            $category->save(); 
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "message" => "records updated failed"
            ], 200);
        }
        


        // if (Category::where('id', $id)->exists()) {
        //     $user_id = Auth::id();
        //     $category = Category::find($id);
        //     if ($category->user_id === $user_id) {
        //         $category->context = is_null($request->context) ? $category->context : $request->context;
        //         $category->save();
        //         return response()->json([
        //             "message" => "records updated successfully"
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             "message" => "not permission to edit"
        //         ], 403); 
        //     }
        // } else {
        //     return response()->json([
        //         "message" => "Category not found"
        //     ], 404);
              
        // }
    }
}
