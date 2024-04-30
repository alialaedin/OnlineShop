<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Modules\Cart\Models\Cart;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Specification\Models\Specification;
use Modules\Store\Models\Store;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model implements HasMedia
{
	use HasFactory, LogsActivity, InteractsWithMedia, HasSlug;

	protected $fillable = [
		'category_id',
		'title',
		'slug',
		'description',
		'status',
		'quantity',
		'price',
		'discount',
		'discount_type',
	];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'محصول ' . __('logs.' . $eventName));
	}

	// Slug
	public function getSlugOptions(): SlugOptions
	{
		return SlugOptions::create()
			->usingLanguage('')
			->generateSlugsFrom('title')
			->saveSlugsTo('slug')
			->slugsShouldBeNoLongerThan(190)
			->doNotGenerateSlugsOnUpdate();
	}

	// Cache
	protected static function booted(): void
	{
		static::deleting(function (Product $product) {
			$messages = 'این محصول قابل حذف نمی باشد زیرا موجودی ان بیشتر از 0 است!';
			if ($product->quantity > 0) {
				throw new ModelCannotBeDeletedException($messages);
			}
		});
	}

	// Relations 
	public function category(): BelongsTo
	{
		return $this->belongsTo(Category::class);
	}

	public function specifications(): BelongsToMany
	{
		return $this->belongsToMany(Specification::class, 'product_specification')
			->withPivot('value');
	}

	public function store(): HasOne
	{
		return $this->hasOne(Store::class);
	}

	public function carts(): HasMany
	{
		return $this->hasMany(Cart::class);
	}

	// Functions
	public function totalPriceWithDiscount(): int
	{
		$price = $this->attributes['price'];
		$discount = $this->attributes['discount'];
		$discountType = $this->attributes['discount_type'];

		if ($discountType === 'percent') {
			return $price - ($price * $discount / 100);
		}
		if ($discountType === 'flat') {
			return $price - $discount;
		}

		return $price;
	}

	public function attachOrSyncSpecifications(array $specifications, string $method)
	{
		$specificationsCollection = collect($specifications)
			->filter(function ($specification) {
				return !is_null($specification['value']);
			})
			->mapWithKeys(function ($specification) {
				return [$specification['id'] => ['value' => $specification['value']]];
			});

		if ($method === 'POST') {
			return $this->specifications()->attach($specificationsCollection->toArray());
		}

		if ($method === 'PATCH') {
			return $this->specifications()->sync($specificationsCollection->toArray());
		}
	}

	// Media Library
	protected $with = ['media'];
	protected $hidden = ['media'];
	protected $appends = ['image', 'galleries'];

	public function registerMediaCollections(): void
	{
		$this->addMediaCollection('product_images')->singleFile();
		$this->addMediaCollection('product_galleries');
	}

	protected function image(): Attribute
	{
		$media = $this->getFirstMedia('product_images');

		return Attribute::make(
			get: fn () => [
				'id' => $media?->id,
				'url' => $media?->getFullUrl(),
				'name' => $media?->file_name
			]
		);
	}

	public function galleries(): Attribute
	{
		$media = $this->getMedia('product_galleries');

		$galleries = $media->map(function ($mediaItem) {
			return [
				'id' => $mediaItem?->id,
				'url' => $mediaItem?->getFullUrl(),
				'name' => $mediaItem?->file_name
			];
		})->toArray();

		return Attribute::make(
			get: fn () => $galleries,
		);
	}

	public function addImage(UploadedFile $file): bool|Media
	{
		return $this->addMedia($file)->toMediaCollection('product_images');
	}

	public function addGallery(UploadedFile $file): bool|Media
	{
		return $this->addMedia($file)->toMediaCollection('product_galleries');
	}

	public function uploadFiles(Request $request): Void
	{
		$this->uploadImage($request);
		$this->uploadGalleries($request);
	}

	protected function uploadImage(Request $request): void
	{
		try {
			if ($request->hasFile('image') && $request->file('image')->isValid()) {
				if ($request->method() == 'PATCH' && $this->getFirstMedia('product_images')) {
					$this->getFirstMedia('product_images')->delete();
				}
				$this->addImage($request->file('image'));
			}
		} catch (FileDoesNotExist $e) {
			Log::error('آپلود فایل برای دسته بندی (فایل وجود ندارد) : ' . $e->getMessage());
		} catch (FileIsTooBig $e) {
			Log::error('آپلود فایل برای دسته بندی (حجم فایل زیاد است) : ' . $e->getMessage());
		}
	}

	protected function uploadGalleries(Request $request): void
	{
		try {
			if ($request->hasFile('galleries')) {
				foreach ($request->file('galleries') as $image) {
					if ($image->isValid()) {
						$this->addGallery($image);
					}
				}
			}
		} catch (FileDoesNotExist $e) {
			Log::error('آپلود فایل برای دسته بندی (فایل وجود ندارد) : ' . $e->getMessage());
		} catch (FileIsTooBig $e) {
			Log::error('آپلود فایل برای دسته بندی (حجم فایل زیاد است) : ' . $e->getMessage());
		}
	}
}
