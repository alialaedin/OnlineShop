<?php

namespace Modules\Home\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Modules\Product\Models\Product;
use Modules\Slider\Models\Slider;

class HomeController extends Controller
{
  public function home()
  {
    $sliders = $this->getActiveSliders(4);
    $latestProducts = $this->getLatestProducts(10);
  }

  private function getActiveSliders(int $number): Builder|Collection
  {
    return Slider::query()
      ->select('id', 'link')
      ->where('status', 1)
      ->take($number)
      ->get();
  }

  private function getLatestProducts(int $number)
  {
    $products = Product::query()
      ->select('id', 'title', 'price', 'discount', 'discount_type', 'category_id')
      ->latest('id')
      ->where('status', 1)
      ->take($number)
      ->get();

    $products->map(function($product) {
      $product->setAttribute('price_with_discount', $product->totalPriceWithDiscount());
    });

    return $products;
  }
}