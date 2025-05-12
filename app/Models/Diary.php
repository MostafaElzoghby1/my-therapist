<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Diary extends Model
{
    use HasFactory;

    protected $fillable = [
        "title" , "content" , "patient_id"
    ];

    public function patient(){
        return $this-> belongsTo(Patient::class);
    }
}
