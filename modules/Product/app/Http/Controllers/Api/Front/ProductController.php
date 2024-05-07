<?php

namespace Modules\Product\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;
use Modules\Product\Models\Product;

class ProductController extends Controller
{
  public function index(): JsonResponse
  {
    $title = request('title');
    $status = request('status');
    $categoryId = request('category_id');
    $minimumPrice = request('minimum_price');
    $maximumPrice = request('maximum_price');
    $withDiscount = request('with_discount');

    $products = Product::query()
      ->select('id', 'title', 'category_id', 'status', 'quantity', 'price', 'slug', 'discount', 'discount_type')
      ->when($title, fn (EloquentBuilder $query) => $query->where('title', 'like', "%$title%"))
      ->when($status, fn (EloquentBuilder $query) => $query->where('status', $status))
      ->when($categoryId, fn (EloquentBuilder $query) => $query->where('category_id', $categoryId))
      ->when($minimumPrice, fn (EloquentBuilder $query) => $query->where('price', '>=', $minimumPrice))
      ->when($maximumPrice, fn (EloquentBuilder $query) => $query->where('price', '<=', $maximumPrice))
      ->when($withDiscount, fn (QueryBuilder $query) => $query->whereNotNull('discount'))
      ->with('category:id,name')
      ->latest('id')
      ->paginate();

    return response()->success('تمام محصولات', compact('products'));
  }

  public function show(Product $product): JsonResponse
  {
    $product->load([
      'category:id,name',
      'specifications:id,name'
    ]);

    return response()->success("محصول شماره {$product->id}", compact('product'));
  }
}
