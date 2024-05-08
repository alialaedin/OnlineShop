<?php

namespace Modules\Home\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Slider\Models\Slider;

class HomeController extends Controller
{
  public function home(): JsonResponse
  {
    $sliders = Slider::select('id', 'link')->where('status', 1)->take(4)->get();
    $latestProducts = $this->getLatestProducts(10);
    $mostDiscountedProducts = $this->getMostDiscountProducts(2);
    $mostVisitedProducts = Product::orderByUniqueViews()->take(10)->get();
    $mostSellingProducts = $this->getMostSellingProducts(10);

    return response()->success(':)', compact(
      'sliders',
      'latestProducts',
      'mostDiscountedProducts',
      'mostVisitedProducts',
      'mostSellingProducts',
    ));
  }

  private function getLatestProducts(int $number): Builder|Collection
  {
    $products = Product::query()
      ->select('id', 'title', 'status', 'price', 'discount', 'discount_type', 'category_id')
      ->with('category:id,name')
      ->available()
      ->latest('id')
      ->take($number)
      ->get();

    $products->map(fn ($product) => $product->setAttribute(
      'price_with_discount',
      $product->totalPriceWithDiscount()
    ));

    return $products;
  }

  private function getMostDiscountProducts(int $number): Builder|Collection
  {
    $mostDiscountedProducts = Product::query()
      ->select(
        'id',
        'title',
        'status',
        'price',
        'discount',
        'discount_type',
        DB::raw(
          'CASE WHEN discount_type = "percent" 
          THEN (price * discount / 100) 
          ELSE discount END 
          AS discount_amout'
        )
      )
      ->whereNotNull(['discount', 'discount_type'])
      ->available()
      ->orderByDesc('discount_amout')
      ->take($number)
      ->get();

    return $mostDiscountedProducts;
  }

  private function getMostSellingProducts(int $number): Collection
  {
    return Product::query()
      ->select('id', 'title', 'status', 'price', 'discount', 'discount_type', 'category_id')
      ->with('category:id,name')
      ->withCount(['orderItems as total_quantity' => function ($query) {
        $query->selectRaw('SUM(quantity)');
      }])
      ->available()
      ->orderByDesc('total_quantity')
      ->take($number)
      ->get();
  }
}
