<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatisticsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function limit(int $default = 10): int
    {
        return $this->integer('limit', $default);
    }
}
