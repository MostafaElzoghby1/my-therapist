<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        "answer" , "reservation_id" , "question_id",
    ];


    public function questions(){
        return $this-> BelongsTo(Question::class);
    }

    public function reservation(){
        return $this-> BelongsTo(Reservation::class);
    }

    

    public function reports(){
        return $this-> BelongsToMany(Report::class);
    }
}
