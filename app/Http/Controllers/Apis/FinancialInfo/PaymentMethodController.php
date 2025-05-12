<?php

namespace App\Http\Controllers\Apis\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentMethodResource;
use App\Models\payment_method;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class PaymentMethodController extends Controller
{
    public function all(){ 
        $userId = Auth::guard('sanctum')->user()->id;

        $methods = PaymentMethod::all();
        $transactions = PaymentMethod::where('user_id', $userId)->get();

        return PaymentMethodResource::collection($methods);
    }
    
    public function show($id){
        $method = PaymentMethod::findOrFail($id);
    
        return PaymentMethodResource::collection($method);
    }
    
    public function add(Request $request){
        $validate = Validator::make($request->all(), [
            "method_name" => "required",
            "owner_name" => "required",
            "user_id" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        PaymentMethod::create([
            "method_name" => $request->method_name,
            "owner_name" => $request->owner_name,
            "user_id" => $request->user_id,
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request, $id){
        $validate = Validator::make($request->all(), [
            "method_name" => "required",
            "owner_name" => "required",
            "user_id" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $method = PaymentMethod::where("id", $id)->first();
        if ($method !== null) {
            $method->update([
                "method_name" => $request->method_name,
                "owner_name" => $request->owner_name,
                "user_id" => $request->user_id,
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
    
    public function delete($id){
        $method = PaymentMethod::where("id", $id)->first();
        if ($method !== null) {
            $method->delete();
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
