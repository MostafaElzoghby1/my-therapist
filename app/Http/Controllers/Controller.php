<?php

namespace App\Http\Controllers;
use App\Models\User;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Transfer;

abstract class Controller
{
    public const PRICE = 500;
    public function saveUserToken($user, $token)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $patient = \Stripe\Customer::create([
            'source' => $token->id,
            'email' => $user->email,
        ]);
       $process = User::where('user_id',$user->id)->update(['stripe_customer_id' => $user->id]);
      
       return $process;
    }

    public  function chargeTransaction($stUserId,$desc){
        $charge = Charge::create([
            'amount' => self::PRICE,
            'currency' => 'egp',
            'customer' => $stUserId,
            'description' => "transaction created",
        ]);
    }
    public  function Transfer($lastChargeId,$drConnectId,$payout){

    Transfer::create([
        'amount' => self::PRICE - (0.2 * self::PRICE),
        "currency" => "usd",
        "source_transaction" => $lastChargeId,
        'destination' => $drConnectId,
    ]);
    }
    public  function firstCharge($token)
{     
         // Create a charge
         $charge = Charge::create([
             'amount' => Self::PRICE,
             'currency' => 'egp', 
             'source' => $token, 
             'description' => 'transaction created'
         ]);
         if($charge->status == 'succeeded'){
            return $charge;
         }else{
            return false;
         }
}
}
