<?php

namespace Modules\Product\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Specification\Models\Specification;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
	use HasFactory, LogsActivity, InteractsWithMedia;

	protected $fillable = [
		'name',
		'parent_id',
		'featured',
		'status'
	];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'دسته بندی ' . __('logs.' . $eventName));
	}

	// Cache
	protected static function clearAllCaches()
	{
		$cacheKeys = ['categories', 'without_parent', 'without_children'];

		foreach ($cacheKeys as $key) {
			if (Cache::has($key)) {
				Cache::forget($key);
			}
		}
	}

	protected static function booted(): void
	{
		static::deleting(function (Category $category) {
			$relations = collect(['children', 'specifications', 'products']);
			$messages = [
				'children' => 'این دسته بندی قابل حذف نمی باشد زیرا دارای زیرمجموعه است!',
				'specifications' => 'این دسته بندی قابل حذف نمی باشد زیرا دارای مشخصه است!',
				'products' => 'این دسته بندی قابل حذف نمی باشد زیرا دارای محصول است!',
			];

			$relations->map(function ($relation) use ($category, $messages) {
				if ($category->{$relation}()->exists()) {
					throw new ModelCannotBeDeletedException($messages[$relation]);
				}
			});
		});
	}

	// Relations
	public function children(): HasMany
	{
		return $this->hasMany(Category::class, 'parent_id');
	}

	public function recursiveChildren(): HasMany
	{
		return $this->children()->with('children');
	}

	public function parent(): BelongsTo
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function specifications(): BelongsToMany
	{
		return $this->belongsToMany(Specification::class, 'category_specification');
	}

	public function products(): HasMany
	{
		return $this->hasMany(Product::class);
	}

	// Media Library
	protected $with = ['media'];
	protected $hidden = ['media'];
	protected $appends = ['image'];

	public function registerMediaCollection(): Void
	{
		$this->addMediaCollection('category_images')->singleFile();
	}

	protected function image(): Attribute
	{
		$media = $this->getFirstMedia('category_images');

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
		return $this->addMedia($file)->toMediaCollection('category_images');
	}

	public function uploadFiles(Request $request): Void
	{
		try {
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				$this->addImage($request->file('image'));
			}
		} catch (FileDoesNotExist $e) {
			Log::error('آپلود فایل برای دسته بندی (فایل وجود ندارد) :' . $e->getMessage());
		} catch (FileIsTooBig $e) {
			Log::error('آپلود فایل برای دسته بندی (حجم فایل زیاد است) :' . $e->getMessage());
		}
	}
}
