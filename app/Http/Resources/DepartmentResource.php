<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'name' => $this->name,
           
            'head_of_department' => new FacultyResource($this->whenLoaded('hod')),
         

           
            'students_count' => $this->whenCounted('students'),
            'faculty_members_count' => $this->whenCounted('facultyMembers'), 

           
        ];
    }
}
