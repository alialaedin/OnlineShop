<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Store\Models\Store;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('store_transactions', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Store::class)->constrained()->cascadeOnDelete();
			$table->unsignedInteger('order_id')->nullable();
			$table->enum('type', ['increment', 'decrement']);
			$table->unsignedBigInteger('quantity');
			$table->text('description')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('store_transactions');
	}
};
