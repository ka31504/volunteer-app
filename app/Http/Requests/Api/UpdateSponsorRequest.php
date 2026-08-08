<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSponsorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'type'     => 'required|in:individual,organization',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|max:255',
            'address'  => 'nullable|string|max:255',
            'tax_code' => 'nullable|string|max:50',
            'notes'    => 'nullable|string',
        ];
    }
}
