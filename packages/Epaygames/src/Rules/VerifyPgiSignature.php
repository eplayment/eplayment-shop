<?php

namespace Epaygames\Rules;

use Illuminate\Contracts\Validation\Rule;

class VerifyPgiSignature implements Rule 
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    protected $valid_signature;

    public function handle($amount, $reference_no)
    {
        $this->valid_signature = hash_hmac('sha256', $amount . '@' . $reference_no, config('app.gateway.signature_key'));

        return $this;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->valid_signature === $value) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return [
            'signature' => __('epaygames::app.gateway.validation.invalid_signature')
        ];
    }
}
