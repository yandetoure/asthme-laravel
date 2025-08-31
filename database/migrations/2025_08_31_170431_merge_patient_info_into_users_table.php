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
        Schema::table('users', function (Blueprint $table) {
            // Informations personnelles du patient
            $table->string('last_name')->nullable()->after('name');
            $table->date('birth_date')->nullable()->after('last_name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
            $table->decimal('height', 5, 2)->nullable()->after('gender'); // en cm
            $table->decimal('weight', 5, 2)->nullable()->after('height'); // en kg
            $table->enum('blood_type', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('weight');
            
            // Informations médicales générales
            $table->text('medical_history')->nullable()->after('blood_type');
            $table->text('allergies')->nullable()->after('medical_history');
            $table->text('family_history')->nullable()->after('allergies');
            $table->text('lifestyle_factors')->nullable()->after('family_history'); // tabac, sport, etc.
            $table->text('current_medications')->nullable()->after('lifestyle_factors');
            $table->text('dosage_instructions')->nullable()->after('current_medications');
            
            // Informations spécifiques à l'asthme
            $table->enum('asthma_severity', ['mild', 'moderate', 'severe'])->default('moderate')->after('dosage_instructions');
            $table->text('asthma_triggers')->nullable()->after('asthma_severity');
            $table->text('inhaler_technique_notes')->nullable()->after('asthma_triggers');
            $table->boolean('uses_peak_flow_meter')->default(false)->after('inhaler_technique_notes');
            $table->boolean('has_action_plan')->default(false)->after('uses_peak_flow_meter');
            
            // Informations de contact d'urgence
            $table->string('emergency_contact_name')->nullable()->after('has_action_plan');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            
            // Informations hospitalières
            $table->string('emergency_hospital')->nullable()->after('emergency_contact_relationship'); // Hôpital en cas de crise
            $table->string('asthma_follow_up_hospital')->nullable()->after('emergency_hospital'); // Hôpital de suivi asthme
            $table->string('emergency_hospital_phone')->nullable()->after('asthma_follow_up_hospital');
            $table->string('asthma_follow_up_hospital_phone')->nullable()->after('emergency_hospital_phone');
            $table->text('hospital_notes')->nullable()->after('asthma_follow_up_hospital_phone');
            
            // Informations médicales
            $table->string('attending_doctor')->nullable()->after('hospital_notes'); // Médecin traitant général
            $table->string('attending_doctor_phone')->nullable()->after('attending_doctor');
            $table->string('asthma_specialist')->nullable()->after('attending_doctor_phone'); // Spécialiste asthme
            $table->string('asthma_specialist_phone')->nullable()->after('asthma_specialist');
            $table->string('insurance_number')->nullable()->after('asthma_specialist_phone');
            $table->text('special_instructions')->nullable()->after('insurance_number');
            
            // Informations administratives
            $table->text('medical_notes')->nullable()->after('special_instructions');
            $table->boolean('is_active_patient')->default(true)->after('medical_notes');
            $table->date('registration_date')->nullable()->after('is_active_patient');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name',
                'birth_date',
                'gender',
                'height',
                'weight',
                'blood_type',
                'medical_history',
                'allergies',
                'family_history',
                'lifestyle_factors',
                'current_medications',
                'dosage_instructions',
                'asthma_severity',
                'asthma_triggers',
                'inhaler_technique_notes',
                'uses_peak_flow_meter',
                'has_action_plan',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'emergency_hospital',
                'asthma_follow_up_hospital',
                'emergency_hospital_phone',
                'asthma_follow_up_hospital_phone',
                'hospital_notes',
                'attending_doctor',
                'attending_doctor_phone',
                'asthma_specialist',
                'asthma_specialist_phone',
                'insurance_number',
                'special_instructions',
                'medical_notes',
                'is_active_patient',
                'registration_date'
            ]);
        });
    }
};
