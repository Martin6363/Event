<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'first_name'     => $this->first_name,
            'last_name'      => $this->last_name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'specialization' => $this->specialization,
            'gender'         => $this->gender,
            'birthday'       => $this->birthday,
            'about'          => $this->about,
            'roles'          => $this->getRoleNames(),
            'settings'       => $this->whenLoaded('settings'),
            'created_at'     => $this->created_at?->toDateTimeString(),
        ];
    }
}
