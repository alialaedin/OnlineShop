<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Customer\Models\Customer;
use Modules\Product\Models\Product;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('carts', function (Blueprint $table) {
      $table->id(); 
      $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
      $table->unsignedInteger('quantity');
			$table->unsignedBigInteger('price');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('carts');
  }
};
