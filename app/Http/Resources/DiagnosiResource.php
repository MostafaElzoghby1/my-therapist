<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiagnosiResource extends JsonResource
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
            "disorders"=>$this->disorders ,
             "type" =>$this->type,
              "degree" =>$this->degree ,
               "excpected_reason"  =>$this->excpected_reason,
                "report" =>new ReportResource($this->report)
            ];
    }
}
