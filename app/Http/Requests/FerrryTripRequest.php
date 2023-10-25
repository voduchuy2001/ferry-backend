<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FerrryTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'between:2,100'],
            'departure_date' => ['required', 'date_format:Y-m-d', 'after:5 hours'],
            'departure_time' => ['required', 'date_format:H:i'],
            'ferry_id' => ['required', 'numeric'],
            'ferry_route_id' => ['required', 'numeric'],
            'trip_type' => ['in:oneway,twoway']
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
