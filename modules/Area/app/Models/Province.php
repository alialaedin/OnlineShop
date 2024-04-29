<?php

namespace Modules\Area\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Core\App\Models\BaseAuthModel;
use Modules\Core\App\Traits\HasCache;

class Province extends BaseAuthModel
{
	use HasCache;

	protected $fillable = 'name';

	protected $casts = [
		'created_at' => Date::class
	];

	public static function booted(): void
	{
		static::deleting(function (Province $province) {
			if ($province->cities()->exists()) {
				throw new ModelCannotBeDeletedException('این استان دارای شهر است و قابل حذف نمی باشد.');
			}
		});

		static::clearAllCaches(['provinces', 'all_provinces']);
	}

	public static function getAllProvinces(): \Illuminate\Support\Collection
	{
		return Cache::rememberForever('provinces', function () {
			return Province::query()
				->where('status', 1)
				->pluck('name', 'id');
		});
	}

	public function cities(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(City::class);
	}
}
