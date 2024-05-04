<?php

namespace Modules\Order\Listeners;

use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\Payment;
use Modules\Order\Events\OrderCreated;

class CreateInvoiceForOrder
{
	/**
	 * Create the event listener.
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 */
	public function handle(OrderCreated $event): void
	{
		$order = $event->order;
		$request = $event->request;

		$invoice = Invoice::create([
			'order_id' => $order->id,
			'amount' => $order->amount,
			'status' => 0
		]);

		Payment::create([
			'invoice_id' => $invoice->id,
			'amount' => $invoice->amount,
			'driver' => $request->driver,
			'tracking_code' => null,
			'description' => null,
			'token' => null,
			'status' => 0
		]);
	}
}
