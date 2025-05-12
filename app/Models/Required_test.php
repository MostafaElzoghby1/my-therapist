<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Required_test extends Model
{
    use HasFactory;

    protected $fillable = [
        "test_name" , "type" , "report_id", "description"
    ];

    public function report(){
        return $this->belongsTo(Report::class);
    }
}
