<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed name
 * @property mixed role_name
 * @property mixed permissions
 */
class RoleStoreRequest extends FormRequest
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
            'name' => 'required|string|max:50',
            'display_name' => 'required|string|max:50',
//            'permissions' => 'required|array',
//            'permissions.*' => 'required|string|exists:permissions,name',
            'organizations' => 'nullable|array',
            'organizations.*' => 'required|exists:roles,id',
        ];
    }
}
