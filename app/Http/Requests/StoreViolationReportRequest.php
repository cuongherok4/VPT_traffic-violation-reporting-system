<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreViolationReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'license_plate' => ['required', 'string', 'max:20'],
            'location' => ['required', 'string', 'max:255'],
            'violation_type' => ['required', 'string', 'max:80'],
            'description' => ['required', 'string', 'max:2000'],
            'violated_at' => ['required', 'date', 'before_or_equal:now'],
            'evidence' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'violated_at.before_or_equal' => 'Thời gian xảy ra vi phạm không được lớn hơn thời điểm hiện tại.',
            'violated_at.date' => 'Thời gian xảy ra vi phạm không hợp lệ.',
        ];
    }
}
