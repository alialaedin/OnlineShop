<?php

namespace Modules\Home\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Modules\Product\Models\Product;
use Modules\Slider\Models\Slider;

class HomeController extends Controller
{
  public function home(): JsonResponse
  {
    $sliders = Slider::select('id', 'link')->where('status', 1)->take(4)->get();
    $latestProducts = $this->getLatestProducts(10);
    $mostDiscountedProducts = $this->getMostDiscountProducts(10);
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
    $discountedProducts = Product::query()
      ->select('id', 'title', 'status', 'price', 'discount', 'discount_type', 'category_id')
      ->with('category:id,name')
      ->whereNotNull(['discount', 'discount_type'])
      ->available()
      ->get();

    $discountedProducts->transform(function ($product) {
      $product->discount_amount = $product->price - $product->totalPriceWithDiscount();
      return $product;
    });

    $mostDiscountedProducts = $discountedProducts->sortByDesc('discount_amount')->take($number);

    $mostDiscountedProducts->transform(function ($product) {
      unset($product['discount_amount']);
      return $product;
    });

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
