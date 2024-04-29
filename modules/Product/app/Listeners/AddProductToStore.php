<?php

namespace Modules\Product\Listeners;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Product\Events\ProductCreated;
use Modules\Store\Models\Store;
use Modules\Store\Models\StoreTransaction;

class AddProductToStore
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
	public function handle(ProductCreated $event): void
	{
		$product = $event->product;

		DB::beginTransaction();

		try {
			$product->store()->create([
				'product_id' => $product->id,
				'balance' => $product->quantity
			]);

			StoreTransaction::query()->create([
				'store_id' => $product->store->id,
				'order_id' => null,
				'type' => 'increment',
				'quantity' => $product->quantity,
				'descrption' => null
			]);
			Store::clearAllCaches();
			DB::commit();
		} catch (Exception $exception) {
			DB::rollBack();
			throw new Exception($exception->getMessage());
		}
	}
}
