<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SponsorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'type'              => $this->type,
            'type_label'        => $this->type_label,
            'phone'             => $this->phone,
            'email'             => $this->email,
            'address'           => $this->address,
            'tax_code'          => $this->tax_code,
            'notes'             => $this->notes,
            // Chỉ tính khi cần (list dài gọi mỗi lần sẽ chậm do query riêng),
            // nên chỉ include ở show(), không include ở list()
            'total_contributed' => $this->when(
                $request->routeIs('*.show'),
                fn () => $this->total_contributed,
            ),
            'donation_count'    => $this->when(
                $request->routeIs('*.show'),
                fn () => $this->donation_count,
            ),
            'donations'         => DonationResource::collection($this->whenLoaded('donations')),
        ];
    }
}
