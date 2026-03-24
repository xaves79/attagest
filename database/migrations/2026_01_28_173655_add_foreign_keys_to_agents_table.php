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
        Schema::table('agents', function (Blueprint $table) {
            $table->foreign(['entreprise_id'], 'agents_entreprise_id_fkey')->references(['id'])->on('entreprises')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['poste_id'], 'fk_agents_poste_id')->references(['id'])->on('postes')->onUpdate('no action')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agents', function (Blueprint $table) {
            $table->dropForeign('agents_entreprise_id_fkey');
            $table->dropForeign('fk_agents_poste_id');
        });
    }
};
