<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            
            'department' => new DepartmentResource($this->whenLoaded('department')),
           
            
            'is_hod_of_department' => $this->whenLoaded('departmentHeaded', function() {
                 return new DepartmentResource($this->departmentHeaded);
            }),
        ];
    }
}