<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        "method_name" , "owner_name" , "user_id" ,"type"
    ];


   

    public function transaction(){
        return $this->hasMany(Payment_transaction::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
