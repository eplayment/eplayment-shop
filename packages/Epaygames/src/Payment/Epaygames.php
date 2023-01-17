<?php

namespace Epaygames\Payment;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Webkul\Payment\Payment\Payment;

class Epaygames extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code  = 'epaygames';

    /**
     * Link expiration in minutes
     */
    protected $link_expiration = 60;

    /**
     * Prefix values before the actual random reference no.
     * 
     * @var string
     */
    public static $transaction_no_prefix = 'EPG';

    /**
     * Reference no. string length after the prefix
     * 
     * @var int
     */
    protected static $transaction_no_length = 13;

    /**
     * Generates reference number
     * 
     * @return string
     */
    protected function generatePaymentTransactionNo() : String
    {
        $cart = $this->getCart();

        $id_to_arbitrary_base = base_convert(($cart->id ?? 0) + 1, 10, 36);

        return Str::upper(
            self::$transaction_no_prefix . $cart->id . '_'. strrev($id_to_arbitrary_base . Str::substr(Str::random(
                    self::$transaction_no_length
                ), Str::length($id_to_arbitrary_base)))
            );
    }

    public function getRedirectUrl() : String
    {
        $transaction = $this->generatePaymentTransaction();

        return $transaction->data->link_url;
    }

    protected function generatePaymentTransaction() : Object
    {
        $cart = $this->getCart();

        $response = Http::epaygames('/biller/links/generate', [
            'amount'                  => $cart->sub_total,
            'reference_no'            => $this->generatePaymentTransactionNo(),
            'callback_webhook_url'    => 'https://eoqqhzmy49wioid.m.pipedream.net',
            // 'callback_webhook_url'    => route('epaygames.callback'),
            'success_redirect_url'    => $this->getConfigData('success_url'),
            'failure_redirect_url'    => $this->getConfigData('failure_url'),
            'link_expires_in_minutes' => $this->link_expiration,
            'expires_in_minutes'      => ($this->link_expiration * 24)
        ]);

        return $response->object();
    }
}