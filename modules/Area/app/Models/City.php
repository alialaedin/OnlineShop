<?php

namespace Modules\Area\Models;

use Illuminate\Support\Facades\Cache;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\App\Traits\Filterable;
use Modules\Core\App\Models\BaseModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class City extends BaseModel
{
	use Filterable, LogsActivity;

	protected $fillable = ['name', 'status'];

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'شهر ' . __('logs.' . $eventName));
	}

	public static function clearAllCaches(): void
	{
		if (Cache::has('all_cities')) {
			Cache::forget('all_cities');
		}
	}

	public static function clearCitiesCacheByProvince(int $provinceId): void
	{
		if (Cache::has('cities_' . $provinceId)) {
			Cache::forget('cities_' . $provinceId);
		}
	}

	protected static function booted(): void
	{
		static::created(fn () => static::clearAllCaches());
		static::updated(fn () => static::clearAllCaches());
		static::deleted(fn () => static::clearAllCaches());

		static::deleting(function (City $city) {
			if ($city->doctors()->exists()) {
				throw new ModelCannotBeDeletedException(
					'این شهر قابل حذف نمی باشد چون در جدول دکترها استفاده شده است!'
				);
			}
		});
	}

	public static function getAllCities(): \Illuminate\Support\Collection
	{
		return Cache::rememberForever('all_cities', function () {
			return City::query()
				->where('status', 1)
				->get(['id', 'name', 'province_id']);
		});
	}

	public function province(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Province::class);
	}
}
