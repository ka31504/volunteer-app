<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDonationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return (new StoreDonationRequest())->rules();
    }

    public function messages(): array
    {
        return (new StoreDonationRequest())->messages();
    }
}