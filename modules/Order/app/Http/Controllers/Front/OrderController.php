<?php

namespace Modules\Order\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\Cart\Models\Cart;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\Payment;
use Modules\Order\Events\OrderCreated;
use Modules\Order\Http\Requests\OrderStoreRequest;
use Modules\Order\Http\Requests\OrderVerifyRequest;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
	public function purchase(OrderStoreRequest $request)
	{
		$customer = Customer::findOrFail(auth('customer-api')->user()->id);
		$address = Address::findOrFail($request->address_id);

		// Create Order
		$order = Order::query()->create([
			'customer_id' => $customer->id,
			'address_id' => $address->id,
			'address' => $address->toJson(),
			'amount' => $customer->calcTheSumOfPricesInCart(),
			'status' => 'wait_for_payment'
		]);

		// Create OrderItem and OrderStatusLog
		Event::dispatch(new OrderCreated($order));

		// Clear Cart
		Cart::where('customer_id', $customer->id)->delete();

		// Create Invoice 
		$invoice = Invoice::create([
			'order_id' => $order->id,
			'amount' => $order->amount,
			'status' => 0
		]);

		// make Payment 
		Payment::create([
			'invoice_id' => $invoice->id,
			'amount' => $invoice->amount,
			'driver' => $request->driver,
			'tracking_code' => null,
			'description' => null,
			'token' => $request->token,
			'status' => 0
		]);
	}

	public function verify(OrderVerifyRequest $request)
	{
		$order = Order::findOrFail($request->order_id);

		try {
			DB::transaction(function () use ($order, $request) {
				$orderStatus = $request->status === 'success' ? 'new' : 'failed';
				$order->update(['status' => $orderStatus]);
				$order->statusLogs()->create(['status' => $orderStatus]);

				if ($orderStatus === 'new') {
					$order->invoice()->update(['status' => 1]);
					$order->payments()->where('status', 0)->update(['status' => 1]);
				} else {
					$order->payments()->where('status', 0)->update(['description' => $request->message]);
				}
			});
		} catch (\Exception $e) {
			Log::error("Failed to verify order: {$e->getMessage()}");
			throw $e;
		}
	}
}
