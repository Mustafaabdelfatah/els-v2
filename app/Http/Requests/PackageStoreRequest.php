<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageStoreRequest extends FormRequest
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
            'name' => 'required|string',
            'desc' => 'nullable|string',
            'type' => 'required|in:monthly,annual',
            'count' => 'required|integer|min:1',
            'price' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
            'is_offer' => 'required|boolean',
            'features' => 'required|array',
            'features.*' => 'array',
            'features.*.id' => 'required|exists:features,id',
            'features.*.max_count' => 'required|integer|min:1',
            'start_date' => 'nullable|date|date_format:Y-m-d|before:expire_date',
            'expire_date' => 'nullable|date|date_format:Y-m-d|after:start_date',
        ];
    }
}
