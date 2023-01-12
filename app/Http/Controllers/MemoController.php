<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MemoController extends Controller
{
    public function getAllMemos() {
        // logic to get all memos goes here
        // $user_id = Auth::id();
        // $memos = User::find(1)->memos->where('parent_id', null);
        // $memos = User::find(1)->memos->where('parent_id', null)->with('child_memos');
        $memos = Memo::where('user_id', 1)->where('parent_id', null)->with('child_memos')
          ->with('child_memos.child_memos')->with('child_memos.child_memos.child_memos')->get();
        // $memos = Memo::get()->toJson(JSON_PRETTY_PRINT);
        // return response($memos, 200);
        return response()->json(['data' => $memos], 200); 
      }
    
      public function createMemo(Request $request) {
        // logic to create a memo record goes here

        $memo = new Memo;
        $memo->title = $request->title;
        $memo->user_id = 1;
        // $memo->parent_id = 8;
        $memo->json = [
          "A" => ["A1", "A2", "A3"],
          "B" => ["B1", "B2", "B3"],
          "C" => ["C1", "C2", "C3"],
          "D" => ["D1", "D2", "D3"],
        ];
        // $memo->json = $request->json;
        $memo->save();
  
        return response()->json([
           "message" => "memo record created"
        ], 201);
      }
    
      public function getMemo($id) {
        // logic to get a memo record goes here

        if (Memo::where('id', $id)->exists()) {
            // $memo = Memo::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            // $user_id = Auth::id();
            // $memo = User::find(1)->memos->where('id', $id)->toJson(JSON_PRETTY_PRINT);
            $memo = User::find(1)->memos->where('id', $id)->first()->json;
            return response($memo, 200);
          } else {
            return response()->json([
              "message" => "Memo not found"
            ], 404);
          }
      }
    
      public function updateMemo(Request $request, $id) {
        // logic to update a memo record goes here

        if (Memo::where('id', $id)->exists()) {
            $memo = Memo::find($id);
            // $memo = User::find(1)->memos;

            // Log::debug("info ログ!", [$request->title]);

            // $memo->title = is_null($request->title) ? $memo->title : $request->title;

            Log::debug('json', $request->json);
            $memo->json = is_null($request->json) ? $memo->json : $request->json;
            $memo->save();
      
            return response()->json([
                "message" => "records updated successfully"
            ], 200);
        } else {
            return response()->json([
                "message" => "Memo not found"
            ], 404);
              
        }
      }
    
      public function deleteMemo ($id) {
        // logic to delete a memo record goes here

        if(Memo::where('id', $id)->exists()) {
            $memo = Memo::find($id);
            $memo->delete();
      
            return response()->json([
              "message" => "records deleted"
            ], 202);
          } else {
            return response()->json([
              "message" => "Memo not found"
            ], 404);
          }
      }
}