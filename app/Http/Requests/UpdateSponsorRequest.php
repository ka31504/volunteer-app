<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSponsorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'type'     => ['required', 'in:individual,organization'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'email'    => ['nullable', 'email', 'max:255'],
            'address'  => ['nullable', 'string', 'max:500'],
            'tax_code' => [
                'nullable', 'string', 'max:50',
                Rule::unique('sponsors', 'tax_code')->ignore($this->route('sponsor')),
            ],
            'notes'    => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Vui lòng nhập tên nhà tài trợ.',
            'type.required'    => 'Vui lòng chọn loại nhà tài trợ.',
            'email.email'      => 'Email không đúng định dạng.',
            'tax_code.unique'  => 'Mã số thuế này đã tồn tại.',
        ];
    }
}