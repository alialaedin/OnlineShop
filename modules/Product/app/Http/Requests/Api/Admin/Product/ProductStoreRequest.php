<?php

namespace Modules\Product\Http\Requests\Api\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\App\Helpers\Helpers;

class ProductStoreRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:191', 'unique:products,id'],
			'category_id' => ['required', 'integer', 'exists:categories,id'],
			'description' => ['required', 'string'],
			'status' => ['required', 'in:draft,available,unavailable'],
			'quantity' => ['required', 'integer', 'min:0'],
			'price' =>  ['required', 'integer', 'min:1'],
			'discount' => ['nullable', 'integer', 'min:1'],
			'discount_type' => ['required', 'in:percent,flat'],
			'image' => ['required', 'image'],
			'galleries' => ['required', 'array'],
			'galleries.*' => ['required', 'image'],
			'specifications' => ['required', 'array'],
			'specifications.*.id' => ['required', 'integer', 'exists:specifications,id'],
			'specifications.*.value' => ['nullable', 'string']
		];
	}

	public function passedValidation()
	{
		$status = $this->input('status');
		$quantity = $this->input('quantity');
		$price = $this->input('price');
		$discount = $this->input('discount');
		$discountType = $this->input('discount_type');

		if ($status == 'available' && $quantity < 1) {
			throw Helpers::makeValidationException('موجودی محصول باید بیشتر از 0 باشد');
		} elseif ($quantity > 0 && $price < 1) {
			throw Helpers::makeValidationException('قیمت محصول نمی تواند 0 باشد');
		} elseif ($discountType == 'percent' && $discount > 100) {
			throw Helpers::makeValidationException('چون نوع تخفیف درصدی است, درصد تخفیف نباید بیشتر از 100 باشد');
		}
	}

	public function validated($key = null, $default = null)
	{
		$validated = parent::validated();
		unset(
			$validated['image'],
			$validated['galleries'],
			$validated['specifications']
		);

		return $validated;
	}

	public function authorize(): bool
	{
		return true;
	}
}
