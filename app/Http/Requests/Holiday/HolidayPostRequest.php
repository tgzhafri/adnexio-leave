<?php

namespace App\Http\Requests\Holiday;

use App\Enums\HolidayType;
use Illuminate\Foundation\Http\FormRequest;

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
            // 'company_id' => 'required',
            'name' => 'required',
            'date' => 'required',
            'day' => 'required',
            'type' => 'sometimes',
            'holiday_type' => 'sometimes:enum_value:' . HolidayType::class,
            'location' => 'sometimes',
            'status' => 'sometimes',
        ];
    }
}
