<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FerryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'between:2,100'],
            'number_of_seats' => ['required', 'numeric', 'min:1'],
            'year_of_production' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'manufacturing_place' => ['required', 'string', 'between:2,100'],
            'seat_ids' => ['required', 'array'],
            'seat_ids.*' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }
}
