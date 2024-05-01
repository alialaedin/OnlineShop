<?php

namespace Modules\Cart\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\App\Exceptions\ModelCannotBeDeletedException;
use Modules\Customer\Models\Customer;
use Modules\Product\Models\Product;

class Cart extends Model
{
	use HasFactory;

	protected $fillable = ['product_id', 'customer_id', 'quantity', 'price'];

	// Relations
	public function product(): BelongsTo
	{
		return $this->belongsTo(Product::class);
	}

	public function customer(): BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	// Query 
	public function scopeWhereCustomerId(Builder $query, int $customerId)
	{
		$query->where('customer_id', $customerId);
	}
	public function scopeSelectAllWithoutTimestamp(Builder $query)
	{
		$query->select('id', 'customer_id', 'product_id', 'quantity', 'price');
	}
}