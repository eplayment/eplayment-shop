<?php

namespace Epaygames\Repositories;

use Epaygames\Payment\Epaygames;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;


class Payment
{
    /**
     * IPN post data.
     *
     * @var array
     */
    protected $post;

    /**
     * Order $order
     *
     * @var \Webkul\Sales\Contracts\Order
     */
    protected $order;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Sales\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\InvoiceRepository  $invoiceRepository
     * @param  Epaygames\Payment\Epaygames  $payment
     * @return void
     */
    public function __construct(
        protected Epaygames $payment,
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository
    )
    {
    }

    /**
     * Extract Cart ID from the reference number.
     */
    public function getOrderId()
    {
        $reference_no = explode('_', $this->post['data']['reference_no']);
        return (int) Arr::last($reference_no);
    }

    /**
     * This function process the callback data sent from epaygames.
     *
     * @param  array  $post
     * @return null|void|\Exception
     */
    public function processCallback($post)
    {
        $this->post = $post;

        try {
            $this->getOrder();
            $this->processOrder();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Load order via IPN invoice id.
     *
     * @return void
     */
    protected function getOrder()
    {
        if (empty($this->order)) {
            $this->order = $this->orderRepository->findOneByField(['id' => $this->getOrderId()]);
        }
    }

    /**
     * Process order and create invoice.
     *
     * @return void
     */
    protected function processOrder()
    {
        if ($this->post['data']['status'] == 'completed') {
            $this->orderRepository->update(['status' => 'processing'], $this->order->id);

            if ($this->order->canInvoice()) {
                $invoice = $this->invoiceRepository->create($this->prepareInvoiceData());
            }
        }
    }

    /**
     * Prepares order's invoice data for creation.
     *
     * @return array
     */
    protected function prepareInvoiceData()
    {
        $invoiceData = ['order_id' => $this->order->id];

        foreach ($this->order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }
}
