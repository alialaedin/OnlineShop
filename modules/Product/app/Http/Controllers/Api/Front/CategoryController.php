<?php

namespace Modules\Product\Http\Controllers\Api\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Product\Models\Category;

class CategoryController extends Controller
{
	public function index(): JsonResponse
	{
		$categories = Category::query()
			->select('id', 'name', 'parent_id', 'status')
			->where('status', 1)
			->with([
				'recursiveChildren:id,name',
				'parent:id,name'
			])
			->latest('id')
			->get();

		return response()->success('تمام دسته بندی های فعال', compact('categories'));
	}
}
