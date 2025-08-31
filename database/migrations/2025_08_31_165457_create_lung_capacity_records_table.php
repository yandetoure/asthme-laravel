<?php  declare(strict_types=1); 

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
        Schema::create('lung_capacity_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('exam_id')->nullable()->constrained()->onDelete('cascade');
            
            // Date et contexte de la mesure
            $table->dateTime('measurement_date');
            $table->enum('measurement_type', ['spirometry', 'peak_flow', 'home_monitoring', 'hospital_test'])->default('spirometry');
            $table->string('performed_by')->nullable(); // médecin ou technicien
            $table->text('notes')->nullable();
            
            // Mesures de base
            $table->decimal('fev1', 8, 2)->nullable(); // Volume expiratoire maximal en 1 seconde (L)
            $table->decimal('fev1_predicted', 8, 2)->nullable(); // Valeur prédite FEV1
            $table->decimal('fev1_percentage', 5, 2)->nullable(); // % de la valeur prédite
            $table->decimal('fvc', 8, 2)->nullable(); // Capacité vitale forcée (L)
            $table->decimal('fvc_predicted', 8, 2)->nullable(); // Valeur prédite FVC
            $table->decimal('fvc_percentage', 5, 2)->nullable(); // % de la valeur prédite
            $table->decimal('fev1_fvc_ratio', 5, 3)->nullable(); // Ratio FEV1/FVC
            $table->decimal('peak_flow', 6, 2)->nullable(); // Débit expiratoire de pointe (L/min)
            $table->decimal('peak_flow_predicted', 6, 2)->nullable(); // Valeur prédite peak flow
            $table->decimal('peak_flow_percentage', 5, 2)->nullable(); // % de la valeur prédite
            
            // Mesures supplémentaires
            $table->decimal('fef25_75', 8, 2)->nullable(); // Débit expiratoire forcé 25-75%
            $table->decimal('fef25_75_predicted', 8, 2)->nullable();
            $table->decimal('fef25_75_percentage', 5, 2)->nullable();
            $table->decimal('tlc', 8, 2)->nullable(); // Capacité pulmonaire totale
            $table->decimal('rv', 8, 2)->nullable(); // Volume résiduel
            $table->decimal('dlco', 8, 2)->nullable(); // Capacité de diffusion du CO
            
            // Conditions de test
            $table->enum('test_condition', ['before_bronchodilator', 'after_bronchodilator', 'baseline'])->default('baseline');
            $table->integer('test_quality_score')->nullable(); // Qualité de l'effort (1-5)
            $table->boolean('test_acceptable')->default(true);
            $table->text('test_quality_notes')->nullable();
            
            // Facteurs environnementaux
            $table->decimal('temperature', 4, 1)->nullable(); // Température ambiante (°C)
            $table->decimal('humidity', 5, 2)->nullable(); // Humidité relative (%)
            $table->decimal('altitude', 6, 1)->nullable(); // Altitude (m)
            $table->text('environmental_notes')->nullable();
            
            // Interprétation
            $table->enum('interpretation', ['normal', 'obstructive', 'restrictive', 'mixed', 'borderline'])->nullable();
            $table->text('interpretation_notes')->nullable();
            $table->text('recommendations')->nullable();
            
            // Statut et suivi
            $table->enum('status', ['pending', 'completed', 'cancelled', 'invalid'])->default('completed');
            $table->boolean('requires_follow_up')->default(false);
            $table->date('follow_up_date')->nullable();
            
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index(['patient_id', 'measurement_date']);
            $table->index(['patient_id', 'measurement_type']);
            $table->index(['measurement_date', 'measurement_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lung_capacity_records');
    }
};
