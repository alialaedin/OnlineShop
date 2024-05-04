<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Modules\Order\Models\Order;

class OrderCreated
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public function __construct(public Order $order, public Request $request)
	{
		$this->order = $order;
		$this->request = $request;
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
