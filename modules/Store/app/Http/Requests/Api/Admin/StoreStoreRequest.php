<?php

namespace Modules\Store\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'product_id' => ['required', 'integer', 'exists:products,id'],
			'type' => ['required', 'in:increment,decrement'],
			'quantity' => ['required', 'integer'],
			'description' => ['nullable', 'string']
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
