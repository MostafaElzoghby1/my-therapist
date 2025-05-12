<?php

namespace App\Http\Controllers\Apis\Reservation;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class EndByPatientController extends Controller
{
    //before completed
    public function __invoke($reservationId)
    {
        $reservation = Reservation::where('id',$reservationId)->first();
        $start = $reservation->start_at;
        $end = $reservation->end_at;
        $oneHourBeforeStart = $start->subHour();
        $now = now();
        
        if($now < $oneHourBeforeStart )
        {
            //retrieve mony for patient
            //update reservation
            //update app
        }elseif($now >$oneHourBeforeStart && $now < $start)
        {
            //take20 from patient then retrieve 
            //update app
        }elseif($now >= $start){
            // تحديث حالة الحجز إلى "في انتظار التقرير"
        }else{
            return response()->json('process failed',301);
        }
//     switch (true) {
//         case ($now < $oneHourBeforeStart):
//             // استرجاع الأموال للمريض
//             // حذف الحجز
//             break;
            
//         case ($now > $oneHourBeforeStart && $now < $end):
//             break;
    
//         default:
//             // return error process
//             break;
//     }

//    }
    //return mony to patient
    //less rate 
    //update app to inactive
     
    //after app and befor create report ->prevent
    
    //aftercreated 
    //complete payment

}
}
