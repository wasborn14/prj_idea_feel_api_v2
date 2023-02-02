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
        $idea->context = [];
        $idea->save();
        return response($idea->id, 201);
    }

    public function getIdea($id) {

        $idea = Idea::find($id);
        if ($idea->exists()) {
            $user_id = Auth::id();
            if ($idea->user_id === $user_id) {
                $idea_context = $idea->context; 
                return response($idea_context, 200);
            } else {
                return response()->json([
                    "message" => "not permission to get"
                ], 403); 
            } 
            $idea = User::find($user_id)->ideas->where('id', $id)->first()->context;
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
                $idea->context = is_null($request->context) ? $idea->context : $request->context;
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
