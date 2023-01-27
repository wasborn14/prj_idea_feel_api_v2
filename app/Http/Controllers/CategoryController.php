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

    public function getCategory($id) {

        if (Category::where('id', $id)->exists()) {
            $user_id = Auth::id();
            $category = Category::find($id);
            if ($category->user_id === $user_id) {
                $category_context = $category->context; 
                return response($category_context, 200);
            } else {
                return response()->json([
                    "message" => "not permission to get"
                ], 403); 
            } 
            $category = User::find($user_id)->categories->where('id', $id)->first()->context;
            return response($category, 200);
          } else {
            return response()->json([
              "message" => "Category not found"
            ], 404);
        }
    }

    public function updateCategory(Request $request, $id) {

        if (Category::where('id', $id)->exists()) {
            $user_id = Auth::id();
            $category = Category::find($id);
            if ($category->user_id === $user_id) {
                $category->context = is_null($request->context) ? $category->context : $request->context;
                $category->save();
                return response()->json([
                    "message" => "records updated successfully"
                ], 200);
            } else {
                return response()->json([
                    "message" => "not permission to edit"
                ], 403); 
            }
        } else {
            return response()->json([
                "message" => "Category not found"
            ], 404);
              
        }
    }
}
