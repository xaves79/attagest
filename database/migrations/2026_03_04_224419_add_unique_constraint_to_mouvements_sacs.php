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
		Schema::table('mouvements_sacs', function (Blueprint $table) {
			// On crée un index unique sur plusieurs colonnes pour éviter les doublons
			$table->unique(['stock_sac_id', 'quantite', 'type_mouvement', 'agent_id', 'date_mouvement'], 'unique_mouvement');
		});
	}

	public function down()
	{
		Schema::table('mouvements_sacs', function (Blueprint $table) {
			$table->dropUnique('unique_mouvement');
		});
	}
};
