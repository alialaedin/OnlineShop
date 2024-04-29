<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Area\Models\City;
use Modules\Customer\Models\Customer;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('addresses', function (Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
			$table->string('name');
			$table->string('mobile');
			$table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();
			$table->text('address');
			$table->string('postal_code', 10);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('adresses');
	}
};
