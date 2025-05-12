<?php

namespace App\Http\Controllers\Apis\Main;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentTransactionResource;
use App\Models\Payment_transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PaymentTransactionController extends Controller
{
    public function all()
    {
        $userId = Auth::guard('sanctum')->user()->id;
    
        $transactions = Payment_transaction::where('user_id', $userId)->get();
    
        return PaymentTransactionResource::collection($transactions);
    }
    
    public function show($id){ // each show what has
        $transaction = Payment_transaction::findOrFail($id);
    
        return PaymentTransactionResource::make($transaction);
    }
    
    
}