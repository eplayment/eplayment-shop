<?php

namespace Epaygames\Http\Requests;

use Epaygames\Rules\VerifyPgiSignature;
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
        $data = (object) $this->validationData()['data'];

        return [
            'data.amount' => 'required|numeric',
            'data.reference_no' => 'required|string',
            'data.signature' => [
                'required', 'string',
                (new VerifyPgiSignature())->handle($data->amount, $data->reference_no)
            ]
        ];
    }
}