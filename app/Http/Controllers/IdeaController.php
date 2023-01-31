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
        // $idea->context = [
        //     "A" => ["A1", "A2", "A3"],
        //     "B" => ["B1", "B2", "B3"],
        //     "C" => ["C1", "C2", "C3"],
        //     "D" => ["D1", "D2", "D3"],
        //   ];
        $idea->save();
  
        // return response()->json([
        //    "message" => "idea record created"
        // ], 201);
        return response($idea->id, 201);
    }

    public function getIdea($id) {

        if (Idea::where('id', $id)->exists()) {
            $user_id = Auth::id();
            $idea = Idea::find($id);
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

        if (Idea::where('id', $id)->exists()) {
            $user_id = Auth::id();
            $idea = Idea::find($id);
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

    // public function getAllMemos() {
    //     // logic to get all memos goes here
    //     // $user_id = Auth::id();
    //     // $memos = User::find(1)->memos->where('parent_id', null);
    //     // $memos = User::find(1)->memos->where('parent_id', null)->with('child_memos');
    //     $memos = Memo::where('user_id', 1)->where('parent_id', null)->with('child_memos')
    //       ->with('child_memos.child_memos')->with('child_memos.child_memos.child_memos')->get();
    //     // $memos = Memo::get()->toJson(JSON_PRETTY_PRINT);
    //     // return response($memos, 200);
    //     return response()->json(['data' => $memos], 200); 
    //   }


    // public function deleteMemo ($id) {
    //     // logic to delete a memo record goes here

    //     if(Memo::where('id', $id)->exists()) {
    //         $memo = Memo::find($id);
    //         $memo->delete();
      
    //         return response()->json([
    //           "message" => "records deleted"
    //         ], 202);
    //       } else {
    //         return response()->json([
    //           "message" => "Memo not found"
    //         ], 404);
    //       }
    //   }
}
