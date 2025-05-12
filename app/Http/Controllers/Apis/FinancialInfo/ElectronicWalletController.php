<?php

namespace App\Http\Controllers\Apis\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\ElectronicWalletResource;
use App\Models\Electronic_wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElectronicWalletController extends Controller
{
    public function all(){ //error
        $wallets = Electronic_wallet::all();
    
        return ElectronicWalletResource::collection($wallets);
    }
    
    public function show($id){
        $wallet = Electronic_wallet::findOrFail($id);
    
        return ElectronicWalletResource::make($wallet);
    }
    
    public function add(Request $request){
        $validate = Validator::make($request->all(), [
            "payment_method_id" => "required",
            "wallet_name" => "required",
            "account" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        Electronic_wallet::create([
            "payment_method_id" => $request->payment_method_id,
            "wallet_name" => $request->wallet_name,
            "account" => $request->account,
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request, $id){
        $validate = Validator::make($request->all(), [
            "payment_method_id" => "required",
            "wallet_name" => "required",
            "account" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $wallet = Electronic_wallet::where("id", $id)->first();
        if ($wallet !== null) {
            $wallet->update([
                "payment_method_id" => $request->payment_method_id,
                "wallet_name" => $request->wallet_name,
                "account" => $request->account,
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
        $wallet = Electronic_wallet::where("id", $id)->first();
        if ($wallet !== null) {
            $wallet->delete();
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
