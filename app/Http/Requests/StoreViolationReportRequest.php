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
}
