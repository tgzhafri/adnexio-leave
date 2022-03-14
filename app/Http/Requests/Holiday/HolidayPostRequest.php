<?php

namespace App\Http\Requests\Holiday;

use App\Enums\HolidayType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HolidayPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'name' => 'sometimes',
            'date' => 'sometimes',
            'day' => 'sometimes',
            'type' => 'sometimes',
            'holiday_type' => 'sometimes:enum_value:' . HolidayType::class,
            'location' => 'sometimes',
            'status' => 'sometimes',
            // 'holidays.*.name' => 'required',
            // 'holidays.*.date' => 'required',
            // 'holidays.*.day' => 'required',
            // 'holidays.*.type' => 'sometimes',
            // 'holidays.*.holiday_type' => 'sometimes:enum_value:' . HolidayType::class,
            // 'holidays.*.location' => 'sometimes',
            // 'holidays.*.status' => 'sometimes',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }
}
