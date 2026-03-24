<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
	{
		Schema::table('points_vente', function (Blueprint $table) {
			$table->string('code_point', 30)->change();
		});
	}

	public function down(): void
	{
		Schema::table('points_vente', function (Blueprint $table) {
			$table->string('code_point', 10)->change();
		});
	}
};
