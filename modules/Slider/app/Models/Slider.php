<?php

namespace Modules\Slider\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Slider extends Model implements HasMedia
{
	use HasFactory, LogsActivity, InteractsWithMedia;

	protected $fillable = ['link', 'status'];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'اسلایدر ' . __('logs.' . $eventName));
	}

	// Media Library
	protected $with = ['media'];
	protected $hidden = ['media'];
	protected $appends = ['image'];

	public function registerMediaCollection(): Void
	{
		$this->addMediaCollection('slider_images')->singleFile();
	}

	protected function image(): Attribute
	{
		$media = $this->getFirstMedia('slider_images');

		return Attribute::make(
			get: fn () => [
				'id' => $media?->id,
				'url' => $media?->getFullUrl(),
				'name' => $media?->file_name
			]
		);
	}

	public function addImage(UploadedFile $file): bool|Media
	{
		return $this->addMedia($file)->toMediaCollection('slider_images');
	}

	public function uploadFiles(Request $request): Void
	{
		try {
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				if ($request->method() == 'PATCH' && $this->getFirstMedia('slider_images')) {
					$this->getFirstMedia('slider_images')->delete();
				}
				$this->addImage($request->file('image'));
			}
		} catch (FileDoesNotExist $e) {
			Log::error('آپلود فایل برای اسلایدر (فایل وجود ندارد) :' . $e->getMessage());
		} catch (FileIsTooBig $e) {
			Log::error('آپلود فایل برای اسلایدر (حجم فایل زیاد است) :' . $e->getMessage());
		}
	}
}
