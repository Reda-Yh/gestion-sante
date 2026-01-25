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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // Patient (user avec rôle patient)
            $table->foreignId('patient_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Médecin (user avec rôle medecin)
            $table->foreignId('medecin_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Date et heure du rendez-vous
            $table->dateTime('appointment_at');

            // Statut du rendez-vous
            $table->enum('status', [
                'en_attente',
                'confirme',
                'annule'
            ])->default('en_attente');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
