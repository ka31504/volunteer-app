<?php

namespace App\Http\Requests;

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
            'project_id'        => ['required', 'exists:projects,id'],
            'full_name'         => ['required', 'string', 'max:255'],
            'phone'             => ['nullable', 'string', 'max:20'],
            'email'             => ['nullable', 'email', 'max:255'],
            'birth_date'        => ['nullable', 'date', 'before:today'],
            'gender'            => ['required', 'in:male,female,other'],
            'address'           => ['nullable', 'string', 'max:500'],
            'joined_at'         => ['required', 'date'],
            'ended_at'          => ['nullable', 'date', 'after_or_equal:joined_at'],
            'hours_contributed' => ['required', 'integer', 'min:0', 'max:9999'],
            'role'              => ['required', 'in:volunteer,team_lead,coordinator'],
            'status'            => ['required', 'in:active,inactive,pending'],
            'notes'             => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required'    => 'Vui lòng chọn dự án.',
            'project_id.exists'      => 'Dự án không hợp lệ.',
            'full_name.required'     => 'Vui lòng nhập họ và tên.',
            'email.email'            => 'Email không đúng định dạng.',
            'birth_date.before'      => 'Ngày sinh phải trước ngày hôm nay.',
            'joined_at.required'     => 'Vui lòng chọn ngày tham gia.',
            'ended_at.after_or_equal'=> 'Ngày kết thúc phải sau hoặc bằng ngày tham gia.',
            'hours_contributed.min'  => 'Số giờ không được âm.',
        ];
    }
}