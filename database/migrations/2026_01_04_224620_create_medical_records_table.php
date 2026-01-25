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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();

            // Patient concerné
            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Médecin qui a rédigé le dossier
            $table->foreignId('medecin_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Rendez-vous associé (optionnel mais recommandé)
            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            // Contenu médical
            $table->text('diagnostic');
            $table->text('traitement')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
