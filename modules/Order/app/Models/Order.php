<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;
use Modules\Invoice\Models\Invoice;

class Order extends Model
{
	use HasFactory;

	protected $fillable = [
		'customer_id',
		'address_id',
		'address',
		'amount',
		'description',
		'status'
	];

	protected static function booted(): void
	{
		static::deleting(function (Order $order) {
			$messages = [
				'items' => 'این سفارش دارای اقلام است و قابل حذف نمی باشد!',
				'invoice' => 'این سفارش دارای صورتحساب است و قابل حذف نمی باشد!'
			];

			if ($order->items()->exists()) {
				throw new ModelCannotBeDeletedException($messages['items']);
			} elseif ($order->invoice()->exists()) {
				throw new ModelCannotBeDeletedException($messages['invoice']);
			}
		});
	}

	// Relations
	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}
	public function address(): BelongsTo
	{
		return $this->belongsTo(Address::class);
	}

	public function items(): HasMany
	{
		return $this->hasMany(OrderItem::class, 'order_id');
	}

	public function statusLogs(): HasMany
	{
		return $this->hasMany(OrderStatusLog::class, 'order_id');
	}

	public function invoice(): HasOne
	{
		return $this->hasOne(Invoice::class);
	}
}
