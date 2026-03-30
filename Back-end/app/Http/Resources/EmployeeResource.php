<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'matricule' => $this->matricule,
            'position' => $this->position,
            'phone' => $this->phone,
            'office_location' => $this->office_location,
            'department' => $this->whenLoaded('department', fn () => new DepartmentResource($this->department)),
            'photo_url' => $this->photo_url,
            'hire_date' => $this->hire_date,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
