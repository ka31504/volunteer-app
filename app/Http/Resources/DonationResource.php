<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'project_id'         => $this->project_id,
            'sponsor_id'         => $this->sponsor_id,
            'type'               => $this->type,
            'type_label'         => $this->type_label,
            'amount'             => $this->type === 'money' ? (float) $this->amount : null,
            'goods_description'  => $this->goods_description,
            'goods_quantity'     => $this->goods_quantity,
            'formatted_amount'   => $this->formatted_amount,
            'payment_method'     => $this->payment_method,
            'payment_label'      => $this->payment_label,
            // Dùng accessor mask có sẵn trong Donation model — tự động ẩn
            // trừ khi Auth::user()->isAdmin(), không cần logic thêm ở đây.
            'donor_name'         => $this->display_donor_name,
            'donor_phone'        => $this->display_donor_phone,
            'donated_at'         => optional($this->donated_at)->toDateString(),
            'note'               => $this->note,
            'project'            => new ProjectResource($this->whenLoaded('project')),
            // Tên Nhà tài trợ KHÔNG mask (đã xác nhận ở bản 3_15/3_16 bên web)
            'sponsor'            => $this->whenLoaded('sponsor', fn () => [
                'id'   => $this->sponsor->id,
                'name' => $this->sponsor->name,
            ]),
        ];
    }
}
