<?php

namespace App\Http\Requests\Auth;

use App\Rules\Available;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed username
 * @property mixed email
 * @property mixed phone
 * @property mixed password
 * @property mixed roles
 */
class UserUpdateRequest extends FormRequest
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
            'id' => ['required', 'exists:users,id', new Available('users')],
            'name' => 'required|string',
            // 'username' => 'required|string|unique:users,username,'.$this->id,
            'email' => 'required|email|unique:users,email,'.$this->id,
            'phone' => 'nullable|numeric|unique:users,phone,'.$this->id,
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
        ];
    }
}
