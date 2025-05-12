<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        "doctor_id" , "reservation_id" , "comment" , "rate" , "patient_name"
    ];


    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }

    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
