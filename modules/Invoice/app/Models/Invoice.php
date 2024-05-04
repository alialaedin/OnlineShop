<?php

namespace Modules\Invoice\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Order\Models\Order;

class Invoice extends Model
{
	use HasFactory;

	protected $fillable = [
		'order_id',
		'status',
		'amount'
	];

	// Relations
	public function payments(): HasMany
	{
		return $this->hasMany(Payment::class);
	}

	public function order(): BelongsTo
	{
		return $this->belongsTo(Order::class);
	}
}
