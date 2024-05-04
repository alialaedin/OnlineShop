<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Event;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
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

		$order = Order::query()->create([
			'customer_id' => $customer->id,
			'address_id' => $address->id,
			'address' => $address->toJson(),
			'amount' => $customer->calcTheSumOfPricesInCart(),
			'status' => 'wait_for_payment'
		]);

		Event::dispatch(new OrderCreated($order, $request));
	}

	public function verify(OrderVerifyRequest $request)
	{
		//
	}
}
