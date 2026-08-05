<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'project_id'        => 'required|exists:projects,id',
            'donor_name'        => 'required|string|max:100',
            'sponsor_id'        => 'nullable|exists:sponsors,id',
            'donor_phone'       => 'nullable|string|max:20',
            'type'              => 'required|in:money,goods',
            'amount'            => 'required_if:type,money|nullable|numeric|min:1000',
            'goods_description' => 'required_if:type,goods|nullable|string|max:255',
            'goods_quantity'    => 'required_if:type,goods|nullable|integer|min:1',
            'payment_method'    => 'required|in:cash,transfer,other',
            'donated_at'        => 'required|date',
            'note'              => 'nullable|string|max:1000',
            
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required_if'            => 'Vui lòng nhập số tiền khi loại đóng góp là tiền mặt.',
            'goods_description.required_if' => 'Vui lòng mô tả hiện vật.',
            'goods_quantity.required_if'    => 'Vui lòng nhập số lượng hiện vật.',
            'amount.min'                    => 'Số tiền tối thiểu là 1.000 đ',
        ];
    }
}