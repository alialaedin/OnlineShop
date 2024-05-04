<?php

namespace Modules\Order\Listeners;

use Modules\Cart\Models\Cart;
use Modules\Order\Events\OrderCreated;

class EmptyTheCustomerCart
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
		Cart::deleteCustomerCarts();
	}
}
