<?php

namespace Modules\Store\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Product\Models\Product;
use Modules\Store\Events\StoreCreated;
use Modules\Store\Http\Requests\Api\Admin\StoreStoreRequest;
use Modules\Store\Models\Store;

class StoreController extends Controller
{
	public function index(): JsonResponse
	{
		$stores = Cache::rememberForever('stores', function () {
			return Store::query()
				->select('id', 'product_id', 'balance')
				->with('product:id,title')
				->latest('id')
				->paginate();
		});

		return response()->success('تمام اجناس انبار', compact('stores'));
	}

	public function show(Store $store): JsonResponse
	{
		$store->load('transactions:id,store_id,quantity,type,description');

		return response()->success('', compact('store'));
	}

	public function store(StoreStoreRequest $request): JsonResponse
	{
		$product = Product::findOrFail($request->product_id);

		DB::beginTransaction();
		try {

			if ($request->type == 'increment') {
				$product->store->increment('balance', $request->quantity);
			} else {
				$product->store->decrement('balance', $request->quantity);
			}

			Event::dispatch(new StoreCreated($request));
			DB::commit();
			
		} catch (Exception $e) {
			DB::rollBack();

			return response()->error('خطا در انجام عملیات : ' . $e->getMessage());
		}
		
		Store::clearAllCaches();
		$operation = $request->type == 'increment' ? 'افزایش' : 'کاهش';

		return response()->success("تعداد {$product->title} با موفقیت {$operation} یافت!");
	}
}
