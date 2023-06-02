<?php

namespace App\Http\Controllers;

use App\Models\Tab;
use App\Models\User;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TabController extends Controller
{

    /**
     * Get Tab_list
     *
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function getTabList() {
        $user_id = Auth::id(); 
        $tab_list = Tab::where('user_id', $user_id)->first()->tab_list;

        if (!isset($tab_list)) {
            throw new NotFoundException(
                'Resource Not Found',
                404,
                'Does Not Exist This Tab List'
            );
        }

        return response($tab_list, 200);
    }

    /**
     * Update Tab_list
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws InternalServerErrorException
     */
    public function updateTabList(Request $request) {

        if (!$request->has(['tab_list'])) {
            throw new BadRequestException(
                'Required Parameter Not Set',
                400,
                'tab_list is required parameter'
            );
        }

        try {
            $user_id = Auth::id(); 
            $tab = Tab::where('user_id', $user_id)->first();

            if (!$tab) {
                throw new NotFoundException(
                    'Resource Not Found',
                    404,
                    'Does Not Exist This Tab List'
                );
            }

            $tab->tab_list = is_null($request->tab_list) ? $tab->tab_list : $request->tab_list;
            $tab->save();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw new InternalServerErrorException(
                'Failed To Update Tab List',
                500,
                'Internal Server Error'
            );
        }
        
        // return response()->json([
        //     "message" => "records updated successfully"
        // ], 200);

        return response()->json($tab->tab_list, 200);
    }
}
