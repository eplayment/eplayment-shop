<?php

namespace Epaygames\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class VerifyCallbackRequest extends FormRequest
{
    /**
     * Determine if the Configuration is authorized to make this request.
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
            'data.signature' => 'required|string'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
           'data.signature' => 'Invalid Callback'
        ];
    }

    /**
     * Set the attribute name.
     */
    public function attributes()
    {
        return [
            
        ];
    }
}