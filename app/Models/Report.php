<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        "note" , "session_deuration" , "reservation_id"
    ];

    public function diagnoses(){
        return $this->hasMany(diagnosi::class);
    }
    public function requir(){
        return $this->has(Required_test::class);
    }
    public function reservation(){
        return $this->belongsTo(Reservation::class);
    }
}


