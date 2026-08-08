<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id'        => 'required|exists:projects,id',
            'full_name'         => 'required|string|max:255',
            'phone'             => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'birth_date'        => 'nullable|date|before:today',
            'gender'            => 'required|in:male,female,other',
            'address'           => 'nullable|string|max:255',
            'joined_at'         => 'required|date',
            'ended_at'          => 'nullable|date|after_or_equal:joined_at',
            'hours_contributed' => 'nullable|numeric|min:0',
            'role'              => 'required|in:volunteer,team_lead,coordinator',
            'status'            => 'required|in:active,inactive,pending',
            'notes'             => 'nullable|string',
        ];
    }
}
