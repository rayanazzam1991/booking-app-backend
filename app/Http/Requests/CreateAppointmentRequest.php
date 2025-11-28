<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'service_id' => ['required', 'exists:services,id'],
            'health_professional_id' => ['required', 'exists:health_professionals,id'],
            'customer_email' => ['required', 'email'],
            'date' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:now'],
        ];
    }
}
