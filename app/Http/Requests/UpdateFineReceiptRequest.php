<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFineReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'integer', 'min:0', 'max:1000000000'],
            'violation_summary' => ['sometimes', 'string', 'max:3000'],
            'payment_status' => ['sometimes', Rule::in(['unpaid', 'paid', 'cancelled'])],
            'due_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
