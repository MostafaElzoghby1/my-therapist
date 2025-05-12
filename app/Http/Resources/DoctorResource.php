<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            "spicially"=>$this->specially,
            "code"=>$this->code,
            "rate"=>$this->rate,
            "image"=>asset("storage". "/" .$this->profile_image),
            "connection_status"=>$this->connection_status,
            "licesne"=>$this->licence_status,
            "activity_status"=>$this->activity_status,
            'user' => new UserResource($this->user),
        ];
    }
}
