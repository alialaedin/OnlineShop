<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Invoice\Models\Invoice;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Invoice::class)->constrained()->cascadeOnDelete();
			$table->unsignedBigInteger('amount');
			$table->string('driver');
			$table->string('tracking_code')->nullable();
			$table->text('description')->nullable();
			$table->string('token')->nullable();
			$table->boolean('status');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('payments');
	}
};
