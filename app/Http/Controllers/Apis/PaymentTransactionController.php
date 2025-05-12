<?php
//automatic operation probable writen in trait for reservation
namespace App\Http\Controllers\Apis;
use App\Http\Controllers\Controller;

use App\Http\Resources\PaymentTransactionResource;
use App\Models\Payment_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTransactionController extends Controller
{
    public function all(){ // each show what has
        $transactions = Payment_transaction::all();
    
        return PaymentTransactionResource::collection($transactions);
    }
    
    public function show($id){ // each show what has
        $transaction = Payment_transaction::findOrFail($id);
    
        return PaymentTransactionResource::make($transaction);
    }
    
    public function add(Request $request){
        $validate = Validator::make($request->all(), [
            "code" => "required",
            "amount" => "required",
            "transaction_type" => "required",
            "source_account" => "required",
            "destination_account" => "required",
            "time" => "required",
            "status" => "required",
            "en_desc" => "required",
            "ar_desc" => "required",
            "payment_method_id" => "required",
            "reservation_id" => "required",
            "user_id" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        Payment_transaction::create([
            "code" => $request->code,
            "amount" => $request->amount,
            "transaction_type" => $request->transaction_type,
            "source_account" => $request->source_account,
            "destination_account" => $request->destination_account,
            "time" => $request->time,
            "status" => $request->status,
            "en_desc" => $request->en_desc,
            "ar_desc" => $request->ar_desc,
            "payment_method_id" => $request->payment_method_id,
            "reservation_id" => $request->reservation_id,
            "user_id" => $request->user_id,
        ]);
    
        return response()->json([
            "msg" => "added successfully",
        ], 200);
    }
    
    public function update(Request $request, $id){
        $validate = Validator::make($request->all(), [
            "code" => "required",
            "amount" => "required",
            "transaction_type" => "required",
            "source_account" => "required",
            "destination_account" => "required",
            "time" => "required",
            "status" => "required",
            "en_desc" => "required",
            "ar_desc" => "required",
            "payment_method_id" => "required",
            "reservation_id" => "required",
            "user_id" => "required",
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors(),
            ], 301);
        }
    
        $transaction = Payment_transaction::where("id", $id)->first();
        if ($transaction !== null) {
            $transaction->update([
                "code" => $request->code,
                "amount" => $request->amount,
                "transaction_type" => $request->transaction_type,
                "source_account" => $request->source_account,
                "destination_account" => $request->destination_account,
                "time" => $request->time,
                "status" => $request->status,
                "en_desc" => $request->en_desc,
                "ar_desc" => $request->ar_desc,
                "payment_method_id" => $request->payment_method_id,
                "reservation_id" => $request->reservation_id,
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
        $transaction = Payment_transaction::where("id", $id)->first();
        if ($transaction !== null) {
            $transaction->delete();
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
