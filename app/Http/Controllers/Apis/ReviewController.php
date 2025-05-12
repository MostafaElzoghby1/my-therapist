<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function all(){ //doctor and patients show for one doctor id 
        $reviews = Review::all();
    
        return ReviewResource::collection($reviews);
    }
    
    public function show($id){
        $review = Review::findOrFail($id);
    
        return ReviewResource::make($review);
    }
    
    public function add(Request $request){ //patient inner reservation
        $validate = Validator::make($request->all(), [
            "doctor_id" => "required",
            "reservation_id" => "required",
            "comment" => "required",
            "rate" => "required",
            "patient_name" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        Review::create([
            "doctor_id" => $request->doctor_id,
            "reservation_id" => $request->reservation_id,
            "comment" => $request->comment,
            "rate" => $request->rate,
            "patient_name" => $request->patient_name,
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request, $id){ //not allowed
        $validate = Validator::make($request->all(), [
            "doctor_id" => "required",
            "reservation_id" => "required",
            "comment" => "required",
            "rate" => "required",
            "patient_name" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $review = Review::where("id", $id)->first();
        if ($review !== null) {
            $review->update([
                "doctor_id" => $request->doctor_id,
                "reservation_id" => $request->reservation_id,
                "comment" => $request->comment,
                "rate" => $request->rate,
                "patient_name" => $request->patient_name,
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
    
    public function delete($id){ //not allowed only admin
        $review = Review::where("id", $id)->first();
        if ($review !== null) {
            $review->delete();
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
