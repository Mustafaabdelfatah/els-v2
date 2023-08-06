<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * @property mixed name
 * @property mixed pages
 */
class TemplateUpdateRequest extends FormRequest
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
            //'name' => 'required|string|unique:templates,name,' . request()->template->id,
//            'pages' => 'required|array',
//            'pages.*' => 'required|array',
//            'pages.*.title' => 'required|array',
//            'pages.*.title.title' => 'required|string',
//            'pages.*.items' => 'required|array',
//            'pages.*.items.*' => 'required|array',
//            'pages.*.items.*.type' => 'required|in:line,radio,label,text,textarea,checkbox,select,table,tree,file',
//            'pages.*.items.*.label' => 'required|string',
//            'pages.*.items.*.excel_name' => 'nullable|string',
//            'pages.*.items.*.notes' => 'string|nullable',
//            'pages.*.items.*.width' => 'required|string',
//            'pages.*.items.*.height' => 'required|string',
//            'pages.*.items.*.enabled' => 'required',
//            'pages.*.items.*.required' => 'required',
//            'pages.*.items.*.website_view' => 'required',
//            'pages.*.items.*.childList' => 'sometimes|array|nullable',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //  'pages.*.items' => '',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'pages.*.items.required' => 'Cannot add pages without any elements',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();
        throw new HttpResponseException(response()->json([
            'errors' => $errors,
            'message' => 'Validation Error'
        ], 422));
    }
}
