<?php

namespace Modules\Specification\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Product\Models\Category;
use Modules\Specification\Http\Requests\Api\Admin\SpecificationStoreRequest;
use Modules\Specification\Http\Requests\Api\Admin\SpecificationUpdateRequest;
use Modules\Specification\Models\Specification;

class SpecificationController extends Controller
{
	public function index(): JsonResponse
	{
		$categories = Category::query()
			->select('id', 'name')
			->whereNull('parent_id')
			->orWhereDoesntHave('parent')
			->latest('id')
			->get();

		$specifications = Specification::query()
			->select('id', 'name', 'status')
			->with('categories:id,name')
			->latest('id')
			->get();

		return response()->success('تمام مشخصات', compact('specifications', 'categories'));
	}

	public function store(SpecificationStoreRequest $request): JsonResponse
	{
		$specification = Specification::query()->create($request->only('name', 'status'));
		$specification->categories()->attach($request->input('category_ids'));

		return response()->success('مشخصصه جدید با موفقیت ساخته شد!');
	}

	public function update(SpecificationUpdateRequest $request, Specification $specification): JsonResponse
	{
		$specification->query()->update($request->only('name', 'status'));
		$specification->categories()->sync($request->input('category_ids'));

		return response()->success('مشخصصه با موفقیت ویرایش شد!');
	}
	public function destroy(Specification $specification): JsonResponse
	{
		$specification->delete();

		return response()->success('مشخصصه با موفقیت حذف شد!');
	}
}
