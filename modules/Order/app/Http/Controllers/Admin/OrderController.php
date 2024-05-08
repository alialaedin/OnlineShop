<?php

namespace Modules\Order\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Order\Http\Requests\OrderUpdateRequest;
use Modules\Order\Models\Order;

class OrderController extends Controller
{
	public function index(): JsonResponse
	{
		$orders = Order::query()
			->select('id', 'customer_id', 'amount', 'status', 'created_at')
			->latest('id')
			->with('customer:id,name,mobile')
			->paginate();

		return response()->success('تمام سفارشات', compact('orders'));
	}

	public function show(Order $order): JsonResponse
	{
		$order->load([
			'customer:id,name,mobile',
			'address:id,name,city_id,address,postal_code',
			'address.city:id,name,province_id',
			'address.city.province:id,name',
			'items:id,product_id,quantity,price,status',
			'items.product:id,title',
			'statusLogs:id,status',
			'invoice:id,amount,status,created_at',
			'invoice.payments:id,amount,driver,tracking_code,description,status'
		]);

		return response()->success(':)', compact('order'));
	}

	public function changeStatus(OrderUpdateRequest $request, Order $order)
	{
		$order->update(['status' => $request->status]);
		$order->statusLogs()->create(['status' => $request->status]);

		return response()->success('وضعیت سفارش با موفقیت ویرایش شد!');
	}
}
