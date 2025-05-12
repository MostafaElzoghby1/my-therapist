<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "code"=>$this->code,
            "amount"=>$this->amount,
            "time"=>$this->time,
            "status"=>$this->status,
            "Transaction_type"=>$this->transaction_type,
            "source_account"=>$this->source_account,
            "distination_account"=>$this->destination_account,
            "ar_desc"=>$this->ar_desc,
            "en_desc"=>$this->en_desc,
            "user"=>new UserResource($this->user),
            "reservation"=>new ReservationResource($this->reservation),
        ];
    }
}
