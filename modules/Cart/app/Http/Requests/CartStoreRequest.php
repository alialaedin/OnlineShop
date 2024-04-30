<?php

namespace Modules\Cart\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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

		$product = Product::findOrFail($productId);
		$productBalance = $product->store->balance;

		if ($productBalance < $quantity) {
			throw Helpers::makeValidationException("تعداد انتخابی باید کمتر یا مساوی {$productBalance} باشد!");
		}
		
	}

	public function authorize(): bool
	{
		return true;
	}
}
