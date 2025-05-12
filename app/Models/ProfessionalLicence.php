<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfessionalLicence extends Model
{
    use HasFactory;

    protected $guarded = [
         "verificated_at"
    ];


    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
