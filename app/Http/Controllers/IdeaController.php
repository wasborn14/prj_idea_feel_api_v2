<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class IdeaController extends Controller
{
    public function createIdea(Request $request) {
        $user_id = Auth::id();
        $idea = new Idea;
        $idea->user_id = $user_id;
        $idea->idea_list = [];
        $idea->save();
        return response($idea->id, 201);
    }

    public function getIdea($id) {

        $idea = Idea::find($id);
        if ($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea_list = $idea->idea_list; 
                return response($idea_list, 200);
            } else {
                return response()->json([
                    "message" => "not permission to get"
                ], 403); 
            } 
            $idea = User::find($user_id)->ideas->where('id', $id)->first()->idea_list;
            return response($idea, 200);
          } else {
            return response()->json([
              "message" => "Idea not found"
            ], 404);
        }
    }

    public function updateIdea(Request $request, $id) {

        $idea = Idea::find($id);
        if ($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea->idea_list = is_null($request->idea_list) ? $idea->idea_list : $request->idea_list;
                $idea->save();
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
                "message" => "idea not found"
            ], 404);
        }
    }

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
                return response()->json([
                    "message" => "not permission to delete"
                ], 403); 
            }
        } else {
            return response()->json([
              "message" => "Memo not found"
            ], 404);
        }
      }
}
