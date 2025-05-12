<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\RequiredTestResource;
use App\Models\Required_test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequiredTestController extends Controller
{
    public function all(){ //patient show what have
        $answer = Required_test::all();
    
        return RequiredTestResource::collection($answer);
    }
    
    public function show($id){ //patient and doctor
        $question = Required_test::findOrFail($id);
    
        return RequiredTestResource::make($question);
    }
    
    public function add(Request $request){ //doctor
        $validate = Validator::make($request->all(), [
            "test_name" => "required",
            "type" => "required",
            "report_id" => "required",
            "description" => "required"
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        Required_test::create([
            "test_name" => $request->test_name,
            "type" => $request->type,
            "report_id" => $request->report_id,
            "description" => $request->description
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    
    }
    
    public function update(Request $request, $id){ //doctor inner reservation
        $validate = Validator::make($request->all(), [
            "test_name" => "required",
            "type" => "required",
            "report_id" => "required",
            "description" => "required"
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $answer = Required_test::where("id", $id)->first();
        if ($answer !== null) {
    
            $answer->update([
                "test_name" => $request->test_name,
                "type" => $request->type,
                "report_id" => $request->report_id,
                "description" => $request->description
            ]);
    
            return response()->json([
                "msg" => "updated successfully",
            ], 200);
    
        } else {
            return response()->json([
                "msg" => "not found",
            ], 404);
        }
    }
    
    public function delete($id){ //patient
        $answer = Required_test::where("id", $id)->first();
        if ($answer !== null) {
            $answer->delete();
    
            return response()->json([
                "msg" => "deleted successfully",
            ], 200);
        } else {
            return response()->json([
                "msg" => "not found",
            ], 404);
        }
    }
    
}
