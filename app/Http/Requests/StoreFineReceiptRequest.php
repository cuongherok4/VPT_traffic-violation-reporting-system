<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFineReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'integer', 'min:0', 'max:1000000000'],
            'violation_summary' => ['required', 'string', 'max:3000'],
            'issued_at' => ['nullable', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:issued_at'],
        ];
    }
}
