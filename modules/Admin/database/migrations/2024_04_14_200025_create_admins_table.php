<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Admin\Models\Admin;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('admins', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('mobile')->unique();
			$table->string('email')->unique()->nullable();
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->timestamps();
		});

		$admin = Admin::create([
			'name' => 'علی علالدین',
			'mobile' => '09368917169',
			'password' => bcrypt(123456)
		]);

	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('admins');
	}
};
