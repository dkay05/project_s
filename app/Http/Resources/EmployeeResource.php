<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'contact_number' => $this->contact_number,
            'city' => $this->city,
            'state' => $this->state,
            'status' => $this->status,
            'photo_url' => $this->photo_url,
            'date_of_birth' => $this->date_of_birth->format('Y-m-d'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
