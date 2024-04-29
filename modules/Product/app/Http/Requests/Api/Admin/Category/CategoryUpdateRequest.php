<?php

namespace Modules\Product\Http\Requests\Api\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryUpdateRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		$categoryId = $this->route('category')->id;

		return [
			'name' => ['required', 'string', Rule::unique('categories', 'name')->ignore($categoryId)],
			'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
			'featured' => ['required', 'boolean', 'in:0,1'],
			'status' => ['required', 'boolean', 'in:0,1'],
		];
	}

	public function passedValidation(): Void
	{
		$categoryId = $this->route('category')->id;
		
		if ($this->filled('parent_id')) {
			if ($this->input('parent_id') == $categoryId) {
				throw ValidationException::withMessages([
					'parent_id' => ['دسته بندی نمی تواند والد خودش باشد!']
				])
					->errorBag('default');
			}
		}
	}

	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}
}
