<?php

namespace Modules\Store\Listeners;

use Modules\Store\Events\StoreCreated;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;

class CreateTransactionForStore
{
  public function handle(StoreCreated $event): void
  {
    $request = $event->request;
    $store = Store::query()->where('product_id', $request->product_id)->select('id')->firstOrFail();

    StoreTransaction::query()->create([
      'store_id' => $store->id,
      'order_id' => null,
      'type' => $request->type,
      'descrption' => $request->descrption
    ]);
  }
}
