<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recu_lignes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recu_fournisseur_id')->constrained('recus_fournisseurs')->onDelete('cascade');
            $table->foreignId('variete_rice_id')->constrained('varietes_rice')->onDelete('restrict');
            $table->integer('quantite_kg');
            $table->integer('prix_unitaire');
            $table->integer('sous_total');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recu_lignes');
    }
};
