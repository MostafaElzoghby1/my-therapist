<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfessionalLicenceResource extends JsonResource
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
            "syndicate_card"=>asset("storage"). "/" .$this->syndicate_card,
            "status"=>$this->status,
            "verificated_at"=>$this->verificated_at,
            "graduation_certificate"=>asset("storage"). "/" .$this->graduation_certificate,
            "doctor"=>new DoctorResource($this->doctor),
        ];
    }
}
