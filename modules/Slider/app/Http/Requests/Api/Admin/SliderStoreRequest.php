<?php

namespace Modules\Slider\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SliderStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'link' => ['required', 'string'],
			'image' => ['required', 'image'],
			'status' => ['required', 'in:0,1'],
			'status.*' => ['required', 'boolean']
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
