<?php

namespace Modules\Area\App\Http\Requests\Api\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityStoreRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'province_id' => 'bail|required|integer|exists:provinces,id',
			'name' => [
				'required',
				'string',
				'max:191',
				Rule::unique('cities')->where('province_id', $this->input('province_id'))
			],
			'status' => 'required|boolean'
		];
	}

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	protected function prepareForValidation()
	{
		$this->merge([
			'status' => $this->has('status')
		]);
	}
}
