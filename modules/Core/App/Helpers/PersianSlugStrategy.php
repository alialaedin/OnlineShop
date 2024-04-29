<?php

namespace Modules\Core\App\Helpers;

use Spatie\Sluggable\SlugOptions;

class PersianSlugStrategy
{
	public function generateSlug($model, $attribute, $slugFieldName, $slugSuffix)
	{
		$slug = $model->{$attribute};

		$slug = str_replace(' ', '-', $slug);
		$slug = preg_replace('/[^A-Za-z0-9\-]/', '', $slug);

		$slug = $this->ensureUniqueSlug($model, $slugFieldName, $slug, $slugSuffix);

		return $slug;
	}

	protected function ensureUniqueSlug($model, $slugFieldName, $slug, $slugSuffix)
	{
		$slug = $this->makeSlugUnique($model, $slugFieldName, $slug, $slugSuffix);

		return $slug;
	}

	protected function makeSlugUnique($model, $slugFieldName, $slug, $slugSuffix)
	{
		$slug = $this->appendSlugSuffix($slug, $slugSuffix);

		$query = $model->where($slugFieldName, $slug);

		if ($model->exists) {
			$query->where($model->getKeyName(), '!=', $model->getKey());
		}

		$count = $query->count();

		if ($count > 0) {
			$slug = $this->incrementSlug($slug, $count);
		}

		return $slug;
	}

	protected function appendSlugSuffix($slug, $suffix)
	{
		if ($suffix === null) {
			return $slug;
		}

		return $slug . '-' . $suffix;
	}

	protected function incrementSlug($slug, $count)
	{
		$slugParts = explode('-', $slug);

		$slugParts[count($slugParts) - 1] = $slugParts[count($slugParts) - 1] + $count;

		return implode('-', $slugParts);
	}
}
