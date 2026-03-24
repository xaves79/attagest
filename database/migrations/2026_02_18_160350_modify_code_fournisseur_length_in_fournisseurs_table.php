<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
		Schema::table('fournisseurs', function (Blueprint $table) {
			$table->string('code_fournisseur', 20)->change();
		});
	}

	public function down()
	{
		Schema::table('fournisseurs', function (Blueprint $table) {
			$table->string('code_fournisseur', 10)->change();
		});
	}
};
