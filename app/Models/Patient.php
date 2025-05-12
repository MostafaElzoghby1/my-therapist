<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [
        "num_of_reservation" 
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function diaries(){
        return $this->hasMany(Diary::class);
    }

    public function reservation(){
        return $this->hasMany(Reservation::class);
    }
    public $timestamps = false;
}
