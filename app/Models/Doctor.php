<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $guarded = [
         "rate"
    ];
public function user(){
    return $this->belongsTo(User::class);
}

public function prof(){
    return $this->has(ProfessionalLicence::class);
}

public function review(){
    return $this->hasMany(Review::class);
}

public function appointment(){
    return $this->hasMany(DoctorsAppointment::class);
}

public function reservation(){
    return $this->hasMany(Reservation::class);
}

public function questions(){
    return $this->hasMany(Question::class);
}
public $timestamps = false;

}
