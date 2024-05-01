<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Cart\Models\Cart;
use Modules\Core\App\Helpers\Helpers;
use Modules\Product\Models\Product;

class CartStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'product_id' => ['required', 'integer', 'exists:products,id'],
			'quantity' => ['required', 'integer', 'min:1']
		];
	}

	public function passedValidation()
	{
		$productId = $this->product_id;
		$quantity = $this->quantity;

		$product = Product::findOrFail($productId)->with('store:id,balance');
		$productBalance = $product->store->balance;

		if ($productBalance < $quantity) {
			throw Helpers::makeValidationException("تعداد انتخابی محصول باید کمتر یا مساوی {$productBalance} باشد!");
		}

		$carts = Cart::whereIn('customer_id', auth()->user()->id)->get();
		$carts->each(function ($cart) {
			if ($cart->product_id == $this->product_id) {
				throw Helpers::makeValidationException("این محصول در سبد خرید موجود است, در صورت نیاز ان را ویرایش کنید");
			}
		});
	}

	public function authorize(): bool
	{
		return true;
	}
}