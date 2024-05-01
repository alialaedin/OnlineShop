<?php

namespace Modules\Cart\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Modules\Cart\Http\Requests\CartStoreRequest;
use Modules\Cart\Http\Requests\CartUpdateRequest;
use Modules\Cart\Models\Cart;
use Modules\Core\App\Helpers\Helpers;
use Modules\Product\Models\Product;

class CartController extends Controller
{
	public function index(): JsonResponse
	{
		$customerId = auth('customer-api')->user()->id;
		$carts = Cart::whereCustomerId($customerId)
			->selectAllWithoutTimestamp()
			->with([
				'product:id,title,price,discount,discount_type',
				'product.store:id,product_id,balance'
			])
			->get();

		$notifications = $this->checkCart($carts);

		return response()->success('لیست سبد خرید', compact('carts', 'notifications'));
	}

	public function store(CartStoreRequest $request): JsonResponse
	{
		$product = Product::findOrFail($request->product_id);

		Cart::create([
			'product_id' => $request->product_id,
			'customer_id' => auth()->user()->id,
			'quantity' => $request->quantity,
			'price' => $product->totalPriceWithDiscount()
		]);

		return response()->success("محصول جدید ({$product->title}) به سبد خرید اضافه شد");
	}

	public function update(CartUpdateRequest $request, Cart $cart): JsonResponse
	{
		$cart->update($request->validated());

		return response()->success("محصول {$cart->product->title} در سبد خرید با موفقیت بروزرسانی شد");
	}

	public function destory(Cart $cart)
	{
		$cart->delete();

		return response()->success("محصول {$cart->product->title} از سبد خرید با موفقیت حذف شد");
	}

	private function checkCart(Collection $carts): array
	{
		$notifications = [];
		
		foreach ($carts as $cart) {

			$productBalance = $cart->product->store->balance;
			$cartQuantity = $cart->quantity;
			
			$currentPrice = $cart->price;
			$newPrice = $cart->product->totalPriceWithDiscount();

			if ($cartQuantity > $productBalance) {
				$cart->delete();
				$notifications[] = "محصول با عنوان {$cart->product->title} ناموجود شد!";
			} elseif ($currentPrice != $newPrice) {
				$cart->update(['price' => $newPrice]);
				$notifications[] = "قیمت محصول {$cart->product->title} به {$newPrice} تومان تغییر پیدا کرده!";
			}

		}

		return $notifications;
	}
}