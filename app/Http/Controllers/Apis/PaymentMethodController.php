<?php

namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\PaymentMethodResource;
use App\Models\payment_method;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;

class PaymentMethodController extends Controller
{
   

    public function show($id){
        $method = PaymentMethod::findOrFail($id);
    
        return PaymentMethodResource::make($method);
    }
    
    public function add(Request $request){
        $validate = Validator::make($request->all(), [
            "method_name" => "required|in:visa-card,master-card",
            "owner_name" => "required",
        ]);
        Stripe::setApiKey(config('services.stripe.secret')); //

        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
        $userId = Auth::guard('sanctum')->user()->id;

        // if()
        PaymentMethod::create([
            "method_name" => $request->method_name,
            "owner_name" => $request->owner_name,
            "user_id" => $userId,
        ]);
      
        return response()->json([
            "msg" => "added successfully",
        ], 200);
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
