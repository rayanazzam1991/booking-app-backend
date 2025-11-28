<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthProfessionalsListResource extends JsonResource
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
            'name' => $this->user->name,
            'license_number' => $this->license_number,
            'speciality' => $this->speciality,
            'pivot' => HealthProfessionalServicesListResource::make($this->pivot),

        ];
    }
}
