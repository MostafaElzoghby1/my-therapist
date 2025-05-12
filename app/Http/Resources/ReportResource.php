<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            "note"=>$this->note,
             "session_deuration"=>$this->session_deuration ,
             "reservation"=>new ReservationResource($this->reservation)
        ];
    }
}
