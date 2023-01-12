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

        $idea = new Idea;
        $idea->user_id = 1;
        $idea->context = $request->context;
        // $idea->context = [
        //     "A" => ["A1", "A2", "A3"],
        //     "B" => ["B1", "B2", "B3"],
        //     "C" => ["C1", "C2", "C3"],
        //     "D" => ["D1", "D2", "D3"],
        //   ];
        $idea->save();
  
        return response()->json([
           "message" => "idea record created"
        ], 201);
    }

    public function getIdea($id) {

        if (Idea::where('id', $id)->exists()) {
            $user_id = Auth::id();
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
            $idea = Idea::find($id)->where('user_id', $user_id);

            $idea->context = is_null($request->context) ? $idea->context : $request->context;
            $idea->save();
      
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Idea not found"
            ], 404);
              
        }
    }



}
