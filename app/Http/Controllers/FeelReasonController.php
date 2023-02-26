<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\FeelReason;
use App\Models\User;

class FeelReasonController extends Controller
{
    /**
     * Create Feel Reason
     *
     * @return JsonResponse
     * @throws InternalServerErrorException
     */
    public function createFeelReason(Request $request) {
        $user_id = Auth::id();

        try {
            $feel_reason = new FeelReason;
            $feel_reason->title = $request->input('title');
            $feel_reason->user_id = $user_id;
            $feel_reason->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw new InternalServerErrorException(
                'Failed To Create Idea',
                500,
                'Internal Server Error'
            );
        }

        return response()->json([
            "message" => "feel reason created"
         ], 201);
    }

    /**
     * Get Feel Reason List
     *
     * @return array
     */
    public function getFeelReasonList() {
        $user_id = Auth::id(); 
        $feel_reason_list = FeelReason::where('user_id', $user_id)->get()->toArray();
        return response($feel_reason_list, 200);
    }

    /**
     * Create Feel Reason Select List
     *
     * @return array
     */
    public function getFeelReasonSelectList() {
        $user_id = Auth::id(); 
        $feel_reason_list = FeelReason::where('user_id', $user_id)->get();
        $options = [];
        foreach ($feel_reason_list as $feel_reason) {
            $option = new \stdClass();
            $option->value = $feel_reason->id;
            $option->label = $feel_reason->title;
            array_push($options, $option);
        };
        return response()->json([
            'options' => $options,
        ], 200);
    }

    /**
     * Update Feel Reason
     *
     * @return JsonResponse
     * @throws ForbiddenException
     * @throws NotFoundException 
     */
    public function updateFeelReason(Request $request, $id) {
        $feel_reason = FeelReason::find($id);
        if ($feel_reason->exists()) {
            $user_id = Auth::id();
            if ($feel_reason->user_id === $user_id) {
                $feel_reason->title = is_null($request->title) ? $feel_reason->title : $request->title;
                $feel_reason->save();
                return response()->json([
                    "message" => "records updated successfully"
                ], 200);
            } else {
                throw new ForbiddenException(
                    'Not Authorized',
                    403,
                    'Not Authorized'
                );
            }
        } else {
            throw new NotFoundException(
                'Resource Not Found',
                404,
                'Does Not Exist This Feel Reason'
            ); 
        }
    }

    /**
     * Delete Feel Reason
     *
     * @return JsonResponse
     * @throws ForbiddenException
     * @throws NotFoundException 
     */
    public function deleteFeelReason($id) {
        $feel_reason = FeelReason::find($id);
        if($feel_reason->exists()) {
            $user_id = Auth::id();
            if ($feel_reason->user_id === $user_id) {
                $feel_reason->delete();
                return response()->json([
                    "message" => "records delete successfully"
                ], 202);
            } else {
                throw new ForbiddenException(
                    'Not Authorized',
                    403,
                    'Not Authorized'
                );
            }
        } else {
            throw new NotFoundException(
                'Resource Not Found',
                404,
                'Does Not Exist This Feel Reason'
            ); 
        }
    }
}
