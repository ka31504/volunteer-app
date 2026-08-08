<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'role'       => $this->role,
            // User model chưa có getRoleLabelAttribute(), map tạm ở đây.
            // Nếu sau này thêm accessor vào model thì đổi thành $this->role_label.
            'role_label' => match ($this->role) {
                'admin'  => 'Quản trị viên',
                'editor' => 'Biên tập viên',
                default  => 'Người dùng',
            },
        ];
    }
}
