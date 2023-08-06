<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
        $rules =  [
            'name' => 'required|string|max:50|unique:organizations,name,'.$id,
            'description' => 'nullable|string',
        ];

        if($id==null){
            $rules['name'] = 'required|string|unique:organizations,name';
        }
        return $rules;
    }
}
