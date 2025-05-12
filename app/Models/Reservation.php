<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    //status 
    //0-> wait
    //1->started
    //2->time end with out report
    //3->end completly
    protected $fillable = [
        "session_started_at", "session_ended_at","price" , "patient_id","doctor_id"
    ];


    public function patient(){
        return $this->belongsTo(Patient::class);
    }

    public function review(){
        return $this->has(Review::class);
    }

    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function payment(){
        return $this->has(Payment_transaction::class);
    }

    public function answer(){
        return $this->hasMany(Answer::class);
    }

    public function reports(){
        return $this->hasMany(Report::class);
    }

    public static function addReservation($doctorId, $patientId, $appointment,$price)
    {

        $reservation = Reservation::create([
            "doctor_id" => $doctorId,
            "session_started_at" => $appointment->starts_at,
            "session_ended_at" => $appointment->starts_at,
            "day" => $appointment->day,
            "month" => $appointment->month,
            "price" => $price,
            "patient_id" => $patientId,
        ]);
        if($reservation)
        {
            return $reservation;
        }else{
            return false;
        }
       

    }


}
