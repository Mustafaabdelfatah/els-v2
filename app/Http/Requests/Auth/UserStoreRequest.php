<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed name
 * @property mixed username
 * @property mixed email
 * @property mixed phone
 * @property mixed password
 * @property mixed roles
 */
class UserStoreRequest extends FormRequest
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
            // 'username' => 'required|string|unique:users,username',
//            'email' => 'required|email|unique:users,email',
            'email' => 'required|email' . (User::where('email', $this->email)->first() ? '|unique:users,email' : ''),
            'type' => 'required|string',
            'organization_id' => 'required',
            'phone' => 'nullable|numeric|unique:users,phone',
        ];
    }
}
