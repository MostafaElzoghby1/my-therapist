<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            "session_started_at"=>$this->session_started_at,
            "session_ended_at"=>$this->session_ended_at,
            "price"=>$this->price,
            "type"=>$this->type,
            "patient_id"=>new PatientResource($this->patient),
            "doctor_id"=>new DoctorResource($this->doctor),
        ];
    }
}
