<?php

namespace Modules\Order\Listeners;

use Modules\Order\Events\OrderCreated;
use Modules\Order\Models\OrderStatusLog;

class LoggingOfOrderStatus
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
    OrderStatusLog::query()->create([
      'order_id' => $order->id,
      'status' => $order->status
    ]);
  }
}
