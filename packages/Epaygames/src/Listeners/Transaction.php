<?php

namespace Epaygames\Listeners;


use Webkul\Sales\Repositories\OrderTransactionRepository;

class Transaction
{
    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderTransactionRepository  $orderTransactionRepository
     * @return void
     */
    public function __construct(
        protected OrderTransactionRepository $orderTransactionRepository
    )
    {
    }

    /**
     * Save the transaction data for online payment.
     * 
     * @param  \Webkul\Sales\Models\Invoice $invoice
     * @return void
    */
    public function saveTransaction($invoice) {
        $request = request()->all();

        if ($invoice->order->payment->method == 'epaygames') {
            $data = $request['data'];

            $this->orderTransactionRepository->create([
                'transaction_id' => $data['reference_no'],
                'status'         => $data['status'],
                'type'           => $data['transaction']['channel']['name'],
                'payment_method' => $invoice->order->payment->method,
                'order_id'       => $invoice->order->id,
                'invoice_id'     => $invoice->id,
                'amount'         => $data['total_amount'],
                'data'           => json_encode($data),
            ]);
        }
    }
}