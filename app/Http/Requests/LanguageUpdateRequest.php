<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed id
 * @property mixed name
 * @property mixed code
 * @property mixed direction
 * @property mixed flag
 */
class LanguageUpdateRequest extends FormRequest
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
            'id' => 'required|exists:languages,id',
            'name' => 'required|unique:languages,name,' . $this->id,
            'code' => 'required',
            'direction' => 'required',
            'flag' => 'required|string',
        ];
    }
}
