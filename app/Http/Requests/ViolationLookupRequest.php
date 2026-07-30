<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ViolationLookupRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'report_id' => ['nullable', 'integer', 'min:1'],
            'license_plate' => ['nullable', 'string', 'max:20'],
            'status' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->filled('report_id') && ! $this->filled('license_plate') && ! $this->filled('email')) {
                $validator->errors()->add('query', 'Provide report_id, license_plate, or email.');
            }

            if ($this->filled('email') && ! $this->user()) {
                $validator->errors()->add('email', 'Email lookup requires authentication.');
            }
        });
    }
}
