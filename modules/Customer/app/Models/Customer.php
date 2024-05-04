<?php

namespace Modules\Customer\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;
use Modules\Cart\Models\Cart;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Order\Models\Order;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class Customer extends Authenticatable
{
	use HasFactory, HasRoles, HasApiTokens, LogsActivity;

	protected $fillable = [
		'name',
		'mobile',
		'password',
		'email',
		'national_code',
		'mobile_verified_at',
		'status'
	];
	protected $hidden = [
		'password',
	];

	protected function casts(): array
	{
		return [
			'password' => 'hashed',
		];
	}

	protected static function booted(): void
	{
		static::deleting(function (Customer $customer) {
			$messages = [
				'carts' => 'مشتری دارای سبد خرید است و قابل حذف نمی باشد!',
				'orders' => 'برای این مشتری سفارشی ثبت شده است و قابل حذف نمی باشد!'
			];

			if ($customer->carts()->exists()) {
				throw new ModelCannotBeDeletedException($messages['carts']);
			} elseif ($customer->orders()->exists()) {
				throw new ModelCannotBeDeletedException($messages['orders']);
			}
		});
	}

	// Activity Log
	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()
			->logOnly($this->fillable)
			->setDescriptionForEvent(fn (string $eventName) => 'مشتری ' . __('logs.' . $eventName));
	}

	// Relations
	public function addresses(): HasMany
	{
		return $this->hasMany(Address::class);
	}

	public function carts(): HasMany
	{
		return $this->hasMany(Cart::class);
	}

	public function orders(): HasMany
	{
		return $this->hasMany(Order::class);
	}

	// functions
	public function calcTheSumOfPricesInCart()
	{
		return $this->carts()->sum('price');
	}
}
