<?php

namespace App\Http\Controllers\Apis\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\CreditCardResource;
use App\Models\Credit_card;
use App\Traits\SaveUserToken;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreditCardController extends Controller
{
    public function all(){
        $creditCards = Credit_card::all();
    
        return CreditCardResource::collection($creditCards);
    }
    
    public function show($id){
        $creditCard = Credit_card::findOrFail($id);
    
        return CreditCardResource::make($creditCard);
    }
    
    public function add(Request $request){
        $validate = Validator::make($request->all(), [
            "payment_method_id" => "required",
            "card_number" => "required",
            "cvc" => "required",
            "exp_month" => "required",
            "exp_year" => "required"
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
        $user = Auth::guard('sanctum')->user();
        $token = \Stripe\Token::create([
            'card' => [
                'number' => $request->card_number, // Example test card number
                'exp_month' => $request->exp_month, // Example expiration month (e.g., December)
                'exp_year' => $request->exp_year, // Example expiration year
                'cvc' => $request->cvc // Example CVC
            ]
        ]);
        SaveUserToken::saveUserToken($user,$token);
        Credit_card::create([
            "payment_method_id" => $request->payment_method_id,
            "card_number" => $request->card_number,
            "cvv" => $request->cvv,
            "type" => $request->type,
            "expiration_date" => $request->expiration_date,
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request, $id){
        $validate = Validator::make($request->all(), [
            "payment_method_id" => "required",
            "card_number" => "required",
            "cvv" => "required",
            "type" => "required",
            "expiration_date" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $creditCard = Credit_card::where("id", $id)->first();
        if ($creditCard !== null) {
            $creditCard->update([
                "payment_method_id" => $request->payment_method_id,
                "card_number" => $request->card_number,
                "cvv" => $request->cvv,
                "type" => $request->type,
                "expiration_date" => $request->expiration_date,
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
        $creditCard = Credit_card::where("id", $id)->first();
        if ($creditCard !== null) {
            $creditCard->delete();
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
