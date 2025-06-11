<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'date' => $this->date,
            'time' => $this->time,
            'duration' => $this->duration,
            'duration_unit' => $this->duration_unit,
            'gender' => $this->gender,
            'members_count' => $this->members_count,
            'status' => $this->status,
            'banner_url' => $this->getFirstMediaUrl('banner'),
        ];
    }
}
