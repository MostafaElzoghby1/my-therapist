<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorImageResource extends JsonResource
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
            "verifing_image"=>asset("storage"). "/" .$this->verifing_image,
            "personal_image"=>asset("storage"). "/" .$this->personal_image,
            'doctor'=>new DoctorResource($this->doctor)
        ];
    }
}
