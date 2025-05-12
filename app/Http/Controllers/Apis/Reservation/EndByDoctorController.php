<?php

namespace App\Http\Controllers\Apis\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Report;
use App\Models\Reservation;
use App\Traits\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndByDoctorController extends Controller
{
    public function __invoke($reservationId)
    {
        $drUser = Auth::guard('sanctum')->user();
        $stripConnectId = $drUser->strip_connect_id;
        $tokenId = $drUser->strip_token_id;
        $reservation = Reservation::where('id',$reservationId)->first();
        $report = Report::where('reservation_id',$reservationId)->first();
        $chat = Chat::where('reservation_id',$reservationId)->first();

        $start = $reservation->start_at;
        $end = $reservation->end_at;
        $oneHourBeforeStart = $start->subHour();
        $now = now();
      
        if($now < $start){
            //retrive 
            $reservation->status = 4; //cncelled
            $reservation->save();

            return response()->json('reservation cancelled successfully',200);

        }elseif($now > $start && $now < $end || $reservation->status == 0){

            return response()->json('process failed',301);
        }elseif(($now >= $start &&  $report ==null)||$reservation->status == 2){ //dont start
            // تحديث حالة الحجز إلى "في انتظار التقرير"
            $reservation->status = 2;
            $reservation->save();

            return response()->json('fill report first',301);

        }elseif($reservation->status == 3){ //when occur failure
            //Transaction::Transfer()
            Transaction::Transfer($tokenId,$stripConnectId);
            $chat->status = 0;
            $chat->save();

            return response()->json('reservation completed',200);

        }
    //non completed
    //before app by two hours return mony
    //after app loss twnty percent of price
    //update doctor app status = reserve 0->inactive 1->active 2->reserved
    
    //completed 
    //complete payment 
    //update num of resrve by return and add one 
    //add review (new controller)
}
}