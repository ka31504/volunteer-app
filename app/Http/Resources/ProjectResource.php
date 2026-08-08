<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'description'     => $this->description,
            'status'          => $this->status,
            'target_amount'   => (float) $this->target_amount,
            'current_amount'  => (float) $this->current_amount,
            'progress_percent'=> $this->progressPercentage(),
            'start_date'      => optional($this->start_date)->toDateString(),
            'end_date'        => optional($this->end_date)->toDateString(),
            // Chỉ kèm khi được eager-load ở show(), tránh N+1 ở list()
            'donations'       => DonationResource::collection($this->whenLoaded('donations')),
            'participants'    => ParticipantResource::collection($this->whenLoaded('participants')),
        ];
    }
}
