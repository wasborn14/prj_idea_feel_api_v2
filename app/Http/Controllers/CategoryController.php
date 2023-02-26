<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Get Category_list
     *
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function getCategoryList() {
        $user_id = Auth::id(); 
        $category_list = Category::where('user_id', $user_id)->first()->category_list;

        if (!isset($category_list)) {
            throw new NotFoundException(
                'Resource Not Found',
                404,
                'Does Not Exist This Category List'
            );
        }

        return response($category_list, 200);
    }

    /**
     * Update Category_list
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     */
    public function updateCategoryList(Request $request) {

        if (!$request->has(['category_list'])) {
            throw new BadRequestException(
                'Required Parameter Not Set',
                400,
                'category_list is required parameter'
            );
        }

        try {
            $user_id = Auth::id(); 
            $category = Category::where('user_id', $user_id)->first();

            if (!$category) {
                throw new NotFoundException(
                    'Resource Not Found',
                    404,
                    'Does Not Exist This Category List'
                );
            }

            $category->category_list = is_null($request->category_list) ? $category->category_list : $request->category_list;
            $category->save();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw new InternalServerErrorException(
                'Failed To Update Category List',
                500,
                'Internal Server Error'
            );
        }
        
        return response()->json([
            "message" => "records updated successfully"
        ], 200);
    }
}
