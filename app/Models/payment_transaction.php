<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment_transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'stripe_payment_id',
        'stripe_transfer_id',
        'status',
        'amount',
        'platform_fee',
        'doctor_earnings',
        'payment_time',
        'transfer_time',
    ];

}
