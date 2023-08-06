<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
        $id = $this->request->get('id');
        $rules = [
            'name_en' => 'required|string|max:50|unique:departments,name_en,' . $id,
            'name_ar' => 'required|string|max:50|unique:departments,name_ar,' . $id,
            'description' => 'nullable|string',
        ];

        if ($id == null) {
            $rules['name_en'] = 'required|string|unique:departments,name_en';
            $rules['name_ar'] = 'required|string|unique:departments,name_ar';
        }
        return $rules;
    }
}
