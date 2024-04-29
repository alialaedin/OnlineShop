<?php

namespace Modules\Core\App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
  public function destroy(Media $media): JsonResponse
  {
    $media->delete();

    return response()->success('عکس با موفقیت حذف شد');
  }
}