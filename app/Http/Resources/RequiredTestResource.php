<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequiredTestResource extends JsonResource
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
            "test_name"=>$this->test_name ,
             "type"=>$this->type ,
             "desc"=>$this->description,
            "report_id"=>new ReportResource($this->report),
        ];
    }
}
