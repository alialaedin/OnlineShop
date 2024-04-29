<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Product\Models\Category;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('products', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->string('slug');
			$table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
			$table->text('description');
			$table->enum('status', ['draft', 'available', 'unavailable']);
			$table->unsignedInteger('quantity')->default(0);
			$table->unsignedBigInteger('price');
			$table->unsignedBigInteger('discount')->nullable();
			$table->enum('discount_type', ['percent', 'flat'])->nullable();
			$table->timestamps();
			// image & images => media library
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('products');
	}
};
