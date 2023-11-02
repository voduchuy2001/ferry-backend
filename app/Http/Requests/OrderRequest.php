<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:15000', 'max:100000000'],
            'ticket_quantity' => ['required', 'integer'],
            'payment_status' => ['nullable'],
            'user_id' => ['nullable'],

            'phone_number' => ['required', 'array'],
            'identity' => ['required', 'array'],
            'name' => ['required', 'array', 'max:50'],
            'date_of_birth' => ['required'],
            'place_of_birth' => ['required', 'array', 'max:100'],
            'nationality' => ['required', 'array', 'max:50'],
            'sex' => ['required', 'array', 'in:male,female'],
            'email' => ['required', 'array'],
            'address' => ['required', 'array', 'max:100'],
            'seat_id' => ['required', 'array'],
            'ferry_trip_id' => ['required', 'array'],
            'ferry_id' => ['required', 'array'],

            'phone_number.*' => ['required'],
            'identity.*' => ['required'],
            'name.*' => ['required', 'string', 'max:50'],
            'date_of_birth.*' => ['required', 'date_format:Y-m-d'],
            'place_of_birth.*' => ['required', 'string', 'max:100'],
            'nationality.*' => ['required', 'string', 'max:50'],
            'sex.*' => ['required', 'in:male,female'],
            'email.*' => ['required', 'email'],
            'address.*' => ['required', 'string', 'max:100'],
            'seat_id.*' => ['required', 'integer'],
            'ferry_trip_id.*' => ['required', 'integer'],
            'ferry_id.*' => ['required', 'integer'],
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
