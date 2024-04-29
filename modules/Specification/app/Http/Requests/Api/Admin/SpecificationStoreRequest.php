<?php

namespace Modules\Specification\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SpecificationStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'unique:specifications,name'],
			'status' => ['required', 'boolean', 'in:0,1'],
			'category_ids' => ['required', 'array'],
			'category_ids.*' => ['required', 'integer'],
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
