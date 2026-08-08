<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // đã chặn ở route middleware admin_or_editor
    }

    public function rules(): array
    {
        return [
            'project_id'        => 'required|exists:projects,id',
            'sponsor_id'        => 'nullable|exists:sponsors,id',
            'donor_name'        => 'required|string|max:255',
            'donor_phone'       => 'nullable|string|max:20',
            'type'              => 'required|in:money,goods',
            // required_if: bắt buộc theo type, giống logic Alpine.js x-show bên web
            // (đã có bài học 3_xx: phải validate cả server-side, không chỉ ẩn/hiện field)
            'amount'            => 'required_if:type,money|nullable|numeric|min:0',
            'goods_description' => 'required_if:type,goods|nullable|string|max:255',
            'goods_quantity'    => 'required_if:type,goods|nullable|integer|min:1',
            'payment_method'    => 'nullable|in:cash,transfer',
            'donated_at'        => 'required|date',
            'note'              => 'nullable|string',
        ];
    }
}
