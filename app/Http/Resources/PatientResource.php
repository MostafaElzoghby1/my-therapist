<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            "user_id"=>$this->user_id,
            "image"=>asset("storage"). "/" .$this->image,
            "num_of_reservation"=>$this->num_of_reservation,
            'user' => new UserResource($this->user),
        ];
    }
}
