<?php

namespace Modules\Product\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Modules\Product\Events\ProductCreated;
use Modules\Product\Http\Requests\Api\Admin\Product\ProductStoreRequest;
use Modules\Product\Http\Requests\Api\Admin\Product\ProductUpdateRequest;
use Modules\Product\Models\Category;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
	public function index(): JsonResponse
	{
		$categories = Category::query()
			->select(['id', 'name'])
			->whereDoesntHave('children')
			->latest('id')
			->get();

		$products = Product::query()
			->select(['id', 'title', 'category_id', 'status', 'quantity', 'price', 'slug'])
			->with('category:id,name')
			->latest('id')
			->paginate();

		return response()->success('تمام محصولات', compact('products', 'categories'));
	}

	public function show(Product $product): JsonResponse
	{
		$product->load([
			'category:id,name',
			'specifications:id,name'
		]);

		return response()->success("محصول شماره {$product->id}", compact('product'));
	}

	public function store(ProductStoreRequest $request): JsonResponse
	{
		$product = Product::query()->create($request->validated());
		$product->uploadFiles($request);
		$product->attachOrSyncSpecifications($request->input('specifications'), 'POST');

		Event::dispatch(new ProductCreated($product));

		return response()->success('محصول جدید با موفقیت ثبت شد!');
	}

	public function update(ProductUpdateRequest $request, Product $product)
	{
		try {
			$product->query()->update($request->validated());
			$product->uploadFiles($request);
			$product->attachOrSyncSpecifications($request->input('specifications'), 'PATCH');

			return response()->success('محصول جدید با موفقیت ثبت شد!');
		} catch (Exception $e) {
			return response()->error('ایجاد محصول با خطا مواجه شد:' . $e->getMessage());
		}
	}
}
