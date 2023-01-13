<?php

namespace Epaygames\Payment;

use Webkul\Payment\Payment\Payment;

class Epaygames extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'epaygames';

    public function getRedirectUrl()
    {
        
    }
}