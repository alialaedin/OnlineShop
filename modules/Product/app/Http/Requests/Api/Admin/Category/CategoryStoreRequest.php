<?php

namespace Modules\Product\Http\Requests\Api\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'unique:categories,name'],
			'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
			'featured' => ['required', 'boolean', 'in:0,1'],
			'status' => ['required', 'boolean', 'in:0,1'],
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
