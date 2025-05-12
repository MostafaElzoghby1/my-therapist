<?php

namespace App\Http\Resources;

use App\Models\Doctor;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            "question"=>$this->question,
            "doctor_id"=>$this->doctor_id,
        ];
    }
}
