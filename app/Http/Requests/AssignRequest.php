<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignRequest extends FormRequest
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
            'form_id' => 'required|integer',
            'user_id' => 'required',
            'date' => 'required|date',
        ];

        if($id==null){
            $rules['form_id'] = 'required|array';
            $rules['form_id.*'] = 'required|integer';
        }

        return $rules;
        
        // return [
        //     // 'form_id' => 'required|integer',
        //     'user_id' => 'required|array', // Allow an array of user IDs
        //     'user_id.*' => 'required|integer', // Validate each user ID in the array
        //     'date' => 'required|date',
        
        // ];

        // $id = $this->request->get('id');
       
        // $rules =  [
        //     'form_id' => 'required|integer',
        //     'user_id' => 'required|array', // Allow an array of user IDs
        //     'user_id.*' => 'required|integer', // Validate each user ID in the array
        //     'date' => 'required|date',
        // ];

        // if($id==null){
        //     $rules['form_id'] = 'required|array';
        //     $rules['form_id.*'] = 'required|integer';
        // }

        // return $rules;
    }
}
