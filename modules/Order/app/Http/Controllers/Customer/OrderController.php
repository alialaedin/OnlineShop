<?php

namespace Modules\Order\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
	public function index(): JsonResponse
	{
		$orders = Order::query()
			->select('id', 'customer_id', 'amount', 'status', 'created_at')
			->where('customer_id', auth('customer-api')->user()->id)
			->latest('id')
			->with('customer:id,name,mobile')
			->paginate();

		return response()->success('تمام سفارشات', compact('orders'));
	}

	public function show(Order $order): JsonResponse
	{
		$customerId = auth('customer-api')->user()->id;
		
		if ($customerId !== $order->customer_id) {
			return response()->error("این سفارش مطعلق به کاربر شماره {$customerId} نیست!");
		}

		$order->load([
			'address:id,name,city_id,address,postal_code',
			'address.city:id,name,province_id',
			'address.city.province:id,name',
			'items:id,product_id,quantity,price',
			'items.product:id,title',
			'invoice:id,amount,created_at',
			'invoice.payments:id,amount,driver,tracking_code,description,status'
		]);

		return response()->success(':)', compact('order'));
	}
}
