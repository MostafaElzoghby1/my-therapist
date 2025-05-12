<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorsAppointment extends Model
{
    use HasFactory;

    protected $guarded = [
       "status"
    ];

    public function appointment(){
        return $this->belongsTo(Appointment::class);
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
    public $timestamps = false;

}
