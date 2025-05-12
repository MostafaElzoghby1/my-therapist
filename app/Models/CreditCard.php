<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    use HasFactory;


    protected $fillable = [
         "stripe_payment_id","card_type"
    ];

    public function payment(){
        return $this->belongsTo(PaymentMethod::class);
    }
    public $timestamps = false;

}
