<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class SettingUpdateRequest extends FormRequest
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
            'data' => 'required',
            // 'data.*.*.key' => 'required|string',
            // 'data.*.value' => 'nullable|string',
            // 'isEnv' => 'required|integer',
            // 'data.*.editable' => 'required|integer',
            // 'data.*.*.type' =>  Rule::in(enum::$types),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json(['message' => 'Validation Error', 'errors' => $errors], 401, $errors));
    }
}

    class enum {
        const text = 'text';
        const textarea = 'textarea';
        const file = 'file';
        const password = 'password';
        const number = 'number';
        const boolean = 'boolean';
        public static $types = [self::text, self::textarea, self::file, self::password , self::number , self::boolean];
    }          
