<?php

namespace Modules\Product\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Product\Http\Requests\Api\Admin\Category\CategoryStoreRequest;
use Modules\Product\Http\Requests\Api\Admin\Category\CategoryUpdateRequest;
use Modules\Product\Models\Category;

class CategoryController extends Controller
{
	public function index(): JsonResponse
	{
		$categories = Category::query()
			->select('id', 'name', 'parent_id', 'featured', 'status')
			->with([
				'parent:id,name',
				'recursiveChildren:id,name,parent_id'
			])
			->latest('id')
			->get();

		return response()->success('تمام دسته بندی ها', compact('categories'));
	}

	public function store(CategoryStoreRequest $request): JsonResponse
	{
		$category = Category::query()->create($request->only('name', 'parent_id', 'featured', 'status'));
		$category->uploadFiles($request);

		return response()->success('دسته بندی جدید با موفقیت ساخته شد!');
	}

	public function update(CategoryUpdateRequest $request, Category $category): JsonResponse
	{
		$category->query()->update($request->only('name', 'parent_id', 'featured', 'status'));
		$category->uploadFiles($request);

		return response()->success("دسته بندی با شناسه {$category->id} با موفقیت ویرایش شد!");
	}
	public function destroy(Category $category): JsonResponse
	{
		$category->delete();

		return response()->success("دسته بندی با شناسه {$category->id} با موفقیت حذف شد!");
	}

	public function getSpecifications(Category $category): JsonResponse
	{
		while ($category->parent) {
			$category = $category->parent;
		}
		$specifications = $category->specifications;

		return response()->success("مشخصه های دسته بندی {$category->name}", compact('specifications'));
	}
}
