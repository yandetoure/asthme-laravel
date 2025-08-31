<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            
            // Informations physiques
            $table->decimal('height', 5, 2)->nullable(); // en cm
            $table->decimal('weight', 5, 2)->nullable(); // en kg
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            
            // Informations médicales
            $table->text('current_medications')->nullable();
            $table->text('dosage_instructions')->nullable();
            $table->text('allergies')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('family_history')->nullable();
            $table->text('lifestyle_factors')->nullable(); // tabac, sport, etc.
            
            // Informations de contact d'urgence
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            
            // Informations médicales spécifiques à l'asthme
            $table->enum('asthma_severity', ['mild', 'moderate', 'severe'])->default('moderate');
            $table->text('asthma_triggers')->nullable();
            $table->text('peak_flow_baseline')->nullable();
            $table->text('inhaler_technique_notes')->nullable();
            $table->boolean('uses_peak_flow_meter')->default(false);
            $table->boolean('has_action_plan')->default(false);
            
            // Informations administratives
            $table->string('insurance_number')->nullable();
            $table->string('primary_care_physician')->nullable();
            $table->string('specialist_physician')->nullable();
            $table->text('special_instructions')->nullable();
            
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['patient_id', 'asthma_severity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_details');
    }
};
