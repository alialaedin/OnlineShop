<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Customer\Models\Address;
use Modules\Customer\Models\Customer;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->id();
      $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
      $table->foreignIdFor(Address::class)->constrained()->cascadeOnDelete();
      $table->json('address');
      $table->unsignedBigInteger('');
      $table->text('description')->nullable();
      $table->enum('status', ['wait_for_payment', 'new', 'in_progress', 'fail', 'delivered']);
      $table->timestamps();
    });
  }
  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
      Schema::dropIfExists('orders');
  }
};