<?php
namespace Epaygames\Http\Controllers;


use Epaygames\Http\Requests\VerifyCallbackRequest;
use Epaygames\Repositories\Payment;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;


class PaymentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Epaygames\Repositories\Payment  $payment
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected Payment $payment
    )
    {
    }

    /**
     * Epaygames callback listener.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback(VerifyCallbackRequest $request)
    {
        $this->payment->processCallback($request->all());
    }

    /**
     * Cancel payment from paypal.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancel()
    {
        session()->flash('error', trans('epaygames::app.checkout.cart.payment-canceled'));

        return redirect()->route('shop.checkout.cart.index');
    }

    /**
     * Success payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function success()
    {
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Cart::deActivateCart();

        session()->flash('order', $order);

        return redirect()->route('shop.checkout.success');
    }
}
