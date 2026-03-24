<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements_traitements', function (Blueprint $table) {
            $table->id();
            $table->string('numero_paiement', 20)->unique();  // PTRT-20260309-0001
            $table->foreignId('traitement_id')->constrained('traitements_client')->onDelete('cascade');
            $table->decimal('montant_paye', 12, 2);
            $table->date('date_paiement');
            $table->string('mode_paiement', 50);  // espèces, mobile_money...
            $table->text('description')->nullable();
            $table->enum('statut', ['paye', 'annule'])->default('paye');
            $table->timestamps();

            $table->index(['traitement_id', 'date_paiement']);
            $table->index('numero_paiement');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements_traitements');
    }
};
