<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed display_name
 * @property mixed group
 */
class PermissionUpdateRequest extends FormRequest
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
            'id' => 'required|exists:permissions,id',
            'name' => 'required|unique:permissions,name,'.$this->id,
            'display_name' => 'required|unique:permissions,display_name,'.$this->id,
            'group' => 'required|string'
        ];
    }
}
