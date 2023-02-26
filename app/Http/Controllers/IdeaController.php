<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\User;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Exceptions\InternalServerErrorException;
use App\Exceptions\ForbiddenException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IdeaController extends Controller
{

    /**
     * Create Idea
     *
     * @return int
     * @throws InternalServerErrCreate
     */
    public function createIdea(Request $request) {

        try {
            $user_id = Auth::id();
            $idea = new Idea;
            $idea->user_id = $user_id;
            $idea->idea_list = [];
            $idea->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            throw new InternalServerErrorException(
                'Failed To Create Idea',
                500,
                'Internal Server Error'
            );
        }

        return response($idea->id, 201);
    }

    /**
     * Get Idea
     *
     * @return JsonResponse
     * @throws ForbiddenException
     * @throws NotFoundException 
     */
    public function getIdea($id) {

        $idea = Idea::find($id);
        if ($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea_list = $idea->idea_list; 
                return response($idea_list, 200);
            } else {
                throw new ForbiddenException(
                    'Not Authorized',
                    403,
                    'Not Authorized'
                );
            } 
            $idea = User::find($user_id)->ideas->where('id', $id)->first()->idea_list;
            return response($idea, 200);
          } else {
            throw new NotFoundException(
                'Resource Not Found',
                404,
                'Does Not Exist This Idea'
            ); 
        }
    }

    /**
     * Update Idea
     *
     * @return JsonResponse
     * @throws ForbiddenException
     * @throws NotFoundException 
     */
    public function updateIdea(Request $request, $id) {

        $idea = Idea::find($id);
        if ($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea->idea_list = is_null($request->idea_list) ? $idea->idea_list : $request->idea_list;
                $idea->save();
                return response()->json([
                    "message" => "idea updated successfully"
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
                'Does Not Exist This Idea'
            ); 
        }
    }

    /**
     * Update Idea
     *
     * @return JsonResponse
     * @throws ForbiddenException
     * @throws NotFoundException 
     */
    public function deleteIdea ($id) {
        $idea = Idea::find($id);
        if($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea->delete();
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
                'Does Not Exist This Idea'
            ); 
        }
      }
}
