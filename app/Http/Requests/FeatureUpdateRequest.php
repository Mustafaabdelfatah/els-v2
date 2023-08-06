<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeatureUpdateRequest extends FormRequest
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
            'id' => 'required|exists:features,id',
            'name' => 'required|unique:features,name,' . $this->id,
            'display_name' => 'required|string',
            'group' => 'required|string',
            'countable' => 'required|boolean',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        ];
    }
}
