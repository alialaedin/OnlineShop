<?php

namespace Modules\Product\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Product\Models\Product;

class ProductCreated
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	/**
	 * Create a new event instance.
	 */
	public Product $product;
	public function __construct(Product $product)
	{
		$this->product = $product;
	}

	/**
	 * Get the channels the event should be broadcast on.
	 */
	public function broadcastOn(): array
	{
		return [
			new PrivateChannel('channel-name'),
		];
	}
}
