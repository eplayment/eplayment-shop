<?php

namespace Epaygames\Payment;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Webkul\Payment\Payment\Payment;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Checkout\Facades\Cart;

class Epaygames extends Payment
{
    /**
     * Payment method code
     *
     * @var string
     */
    protected $code = 'epaygames';

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
     * Epaygames payment API configuration
     */
    protected $payment_config;

    public function __construct(protected OrderRepository $orderRepository)
    {
        $this->payment_config = ($this->getConfigData('sandbox'))
            ? [
                'host'  => config('app.gateway.sandbox.host'),
                'token' => config('app.gateway.sandbox.token')
            ]
            :
            [
                'host'  => config('app.gateway.host'),
                'token' => config('app.gateway.token')
            ];
    }

    /**
     * Generates reference number
     * 
     * @return string
     */
    protected function generatePaymentTransactionNo($order_id) : String
    {
        return self::$transaction_no_prefix . Str::upper(Str::random(self::$transaction_no_length)) . '_' . $order_id;
    }

    public function getRedirectUrl() : String
    {
        $transaction = $this->generatePaymentTransaction();

        return $transaction->data->link_url;
    }

    protected function generatePaymentTransaction() : Object
    {
        $cart  = $this->getCart();
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        $response = Http::epaygames(
            $this->payment_config['host'], $this->payment_config['token'],
            '/biller/links/generate', [
                'amount'                  => $cart->grand_total,
                'reference_no'            => $this->generatePaymentTransactionNo($order->id),
                'callback_webhook_url'    => route('epaygames.callback'),
                'success_redirect_url'    => $this->getConfigData('success_payment_url'),
                'failure_redirect_url'    => $this->getConfigData('failed_payment_url'),
                'link_expires_in_minutes' => $this->link_expiration,
                'expires_in_minutes'      => ($this->link_expiration * 24)
            ]);

        if (!$response->successful()) {
            $order->delete();
        }

        return $response->object();
    }
}