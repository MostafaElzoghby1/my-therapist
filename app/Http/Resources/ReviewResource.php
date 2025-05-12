<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            "doctor"=>new DoctorResource($this->doctor),
            "reservation"=>new ReservationResource($this->reservation),
            "comment"=>$this->comment,
            "rate"=>$this->rate,
            "patient"=>$this->patient_name,
        ];
    }
}
