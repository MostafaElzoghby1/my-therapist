<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentMethodResource extends JsonResource
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
            "method_name"=>$this->method_name,
            "verified_at"=>$this->verified_at,
            "code"=>$this->verification_code,
            "owner_name"=>$this->owner_name,
            "user"=>new UserResource($this->user)
        ];
    }
}
