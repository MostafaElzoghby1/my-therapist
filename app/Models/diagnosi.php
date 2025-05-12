<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class diagnosi extends Model
{
    use HasFactory;

    protected $fillable = [
        "disorders" , "type" , "degree" , "excpected_reson" , "report_id"
    ];

    public function report(){
        return $this->belongsTo(Report::class);
    }
}
