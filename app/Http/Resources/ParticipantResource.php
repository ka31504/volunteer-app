<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParticipantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'project_id'        => $this->project_id,
            'full_name'         => $this->full_name,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'birth_date'        => optional($this->birth_date)->toDateString(),
            'age'               => $this->age,
            'gender'            => $this->gender,
            'gender_label'      => $this->gender_label,
            'address'           => $this->address,
            'joined_at'         => optional($this->joined_at)->toDateString(),
            'ended_at'          => optional($this->ended_at)->toDateString(),
            'duration_days'     => $this->duration_days,
            'hours_contributed' => $this->hours_contributed,
            'role'              => $this->role,
            'role_label'        => $this->role_label,
            'status'            => $this->status,
            'status_label'      => $this->status_label,
            'status_color'      => $this->status_color,
            'notes'             => $this->notes,
        ];
    }
}
