<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'roll_number' => $this->roll_num, 
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'admission_date' => $this->admission_date ? $this->admission_date->toDateString() : null,
            'cet_marks' => $this->cet_marks,
            
            'department' => new DepartmentResource($this->whenLoaded('department')),
           
        ];
    }
}