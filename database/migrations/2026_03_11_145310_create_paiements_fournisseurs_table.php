<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements_fournisseurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recu_fournisseur_id')->constrained('recus_fournisseurs')->onDelete('cascade');
            $table->date('date_paiement');
            $table->decimal('montant', 15, 2);
            $table->enum('mode_paiement', ['espece', 'cheque', 'mobile_money', 'virement'])->default('espece');
            $table->string('reference')->nullable()->unique();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['recu_fournisseur_id', 'date_paiement']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements_fournisseurs');
    }
};
