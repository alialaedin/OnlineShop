<?php

namespace Modules\Slider\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Slider\Http\Requests\Api\Admin\SliderStoreRequest;
use Modules\Slider\Http\Requests\Api\Admin\SliderUpdateRequest;
use Modules\Slider\Models\Slider;

class SliderController extends Controller
{
	public function index(): JsonResponse
	{
		$sliders = Cache::rememberForever('sliders', function () {
			return Slider::query()->select('id', 'link', 'status')->latest('id')->get();
		});

		return response()->success('تمام اسلایدر ها', compact('sliders'));
	}

	public function store(SliderStoreRequest $request): JsonResponse
	{
		try {
			$slider = Slider::query()->create($request->only('link', 'status'));
			$slider->uploadFiles($request);
			Slider::clearAllCaches();

			return response()->success('اسلایدر جدید با موفقیت ساخته شد!');
		} catch (Exception $e) {
			return response()->error('ساخت اسلایدر جدید با خطا مواجه شد :' . $e->getMessage());
		}
	}

	public function update(SliderUpdateRequest $request, Slider $slider): JsonResponse
	{
		try {
			$slider->query()->update($request->only('link', 'status'));
			$slider->uploadFiles($request);
			Slider::clearAllCaches();

			return response()->success('اسلایدر با موفقیت ویرایش شد!');
		} catch (Exception $e) {
			return response()->error("ویرایش اسلایدر با شناسه {$slider->id} با خطا مواجه شد!" . $e->getMessage());
		}
	}
	public function destroy(Slider $slider): JsonResponse
	{
		$slider->delete();
		Slider::clearAllCaches();

		return response()->success("اسلایدر با شناسه {$slider->id} با موفقیت حذف شد!");
	}
}
