<?php

namespace App\Http\Requests\ApprovalRoute;

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
            'name' => 'required|max:50',
            'layer_one' => 'required|max:100',
            'layer_two' => 'sometimes|max:100',
            'layer_three' => 'sometimes|max:100',
        ];
    }
}
