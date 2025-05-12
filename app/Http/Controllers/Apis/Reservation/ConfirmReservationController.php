<?php

namespace App\Http\Controllers\Apis\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\payment\CreditCardRequest;
use App\Http\Requests\payment\ElectronicWalletRequest;
use App\Models\Chat;
use App\Models\Credit_card;
use App\Models\Doctors_appointment;
use App\Models\DoctorsAppointment;
use App\Models\Electronic_wallet;
use App\Models\payment_method;
use App\Models\Question;
use App\Models\Reservation;
use App\Models\User;
use App\Traits\SaveUserToken;
use App\Traits\Transaction;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Account;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Stripe;
use Validator;


class ConfirmReservationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ElectronicWalletRequest $request1, $request2 ,$drUserId, $appointmentId)
    {
        if($request2->method_name == 'credit_card'){
            Validator::make($request2->all(), [
                'stripeToken' => 'required|string',]);

        }elseif($request1->method_name == 'electronic_wallet'){
            $validate = $request1->validated();
        }else {
            return response()->json('error',301);
        }
        

        //return authenticated user
        $pUser = Auth::guard('sanctum')->user();
        $patient = User::join('patients', 'users.id', '=', 'patients.user_id')
            ->where('users.id', $pUser->id)
            ->select('users.*', 'patients.*')->first();       
            
            //return doctor info(included pay)
        $doctor = User::join('doctors', 'users.id', '=', 'doctors.user_id')
            ->findOrFail($drUserId);
            $appointment = DoctorsAppointment::join('appointments', 'doctors_appointments.appointment_id',
             '=', 'appointments.id')->first();


        $method = DoctorsAppointment::where('user_id', $drUserId)->first();
        if ($method->method_name == "credit_card") {
            /**
             * validate  "card_number" => "required",
             *"cvv" => "required",
             *"type" => "required",
             *"expiration_date" => "required",
             */
        //     $info = Credit_card::where('payment_method_id', $method->id)->first();
        // } else {
        //     /**
        //      *validate  "wallet_name" => "required",
        //      *   "account" => "required" 
        //      */
        //     $info = Electronic_wallet::where('payment_method_id', $method->id)->first();
        // }
        // $doctorPay = (array)$method + (array)$info;
        
        //add reservation
        $price = Self::PRICE;
            // pay charge
                Stripe::setApiKey(config('services.stripe.secret'));
                // if($pUser->	strip_token_id == null){
                    $charge = 8;
                   
                // }else{
                //     $token = $pUser->	strip_token_id;
                //     $charge = $this->chargeTransaction($token,'created transcation',$price);
                // }
                   
                if($charge)
                {
                    $reservation = Reservation::addReservation($doctor->id, $patient->id, $appointment,$price = self::PRICE);
                    //update doctor appointment status 
                    if ($reservation) {
                        DoctorsAppointment::where('id', $appointmentId)->update(['status' => 2]);//reserved
            
                            $chat = $this->createChat($pUser->id , $drUserId , $reservation->id);
                            //if exist from previous reserve 

                            $drQuestions = Question::where('doctor_id',$doctor->id)->get();
                            return response()->json([
                                "msg" => "successful reservation",$drQuestions
                            ], 200);
                }else{
                    return response()->json('failed reservation',401);
                }
                
            }
    
        }
    }
      public function createChat($pUserId , $drUserId , $reservationId) 
    {
        $previousChat = $this->getPreviousChat($pUserId , $drUserId);

        if($previousChat === null){

            $chat = Chat::create([
                "p_user_id" => $pUserId,
                "dr_user_id" => $drUserId,
                "reservation_id" => $reservationId
            ]);

            return true;
        }
        $previousChat->status = 1;
        $previousChat->save();
        
        return true;
    }
    
    public function getPreviousChat($pUserId, $drUserId)
    {

        return Chat::where('p_user_id', $pUserId)
            ->where('dr_user_id', $drUserId)
            ->first();
    }


}