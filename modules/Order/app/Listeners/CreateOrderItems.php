<?php

namespace Modules\Order\Listeners;

use Modules\Cart\Models\Cart;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Models\OrderItem;

class CreateOrderItems
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
		$customerId = auth('customer-api')->user()->id;
		$carts = Cart::whereCustomerId($customerId)->get();

		$carts->map(function ($cart) use ($order) {
			OrderItem::query()->create([
				'order_id' => $order->id,
				'product_id' => $cart->product_id,
				'quantity' => $cart->quantity,
				'price' => $cart->price,
				'status' => 1,
			]);
		});
	}
}
