<?php

namespace App\Http\Requests\ApprovalConfig;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'first_approval' => 'required|max:100',
            'second_approval' => 'required|max:100',
            'third_approval' => 'required|max:100',
        ];
    }
}
