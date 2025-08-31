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
            // Informations personnelles
            $table->string('last_name')->nullable()->after('name');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('email');
            $table->date('birth_date')->nullable()->after('gender');
            
            // Informations physiques
            $table->decimal('height', 5, 2)->nullable()->after('birth_date'); // en cm
            $table->decimal('weight', 5, 2)->nullable()->after('height'); // en kg
            $table->string('blood_type', 10)->nullable()->after('weight');
            
            // Informations médicales
            $table->text('medical_history')->nullable()->after('blood_type');
            $table->text('allergies')->nullable()->after('medical_history');
            $table->string('attending_doctor')->nullable()->after('allergies');
            $table->text('current_treatments')->nullable()->after('attending_doctor');
            $table->enum('asthma_severity', ['mild', 'moderate', 'severe'])->default('moderate')->after('current_treatments');
            $table->text('medical_notes')->nullable()->after('asthma_severity');
            
            // Informations d'asthme spécifiques
            $table->text('asthma_triggers')->nullable()->after('medical_notes');
            $table->text('current_medications')->nullable()->after('asthma_triggers');
            $table->text('dosage_instructions')->nullable()->after('current_medications');
            $table->text('family_history')->nullable()->after('dosage_instructions');
            $table->text('lifestyle_factors')->nullable()->after('family_history');
            $table->text('inhaler_technique_notes')->nullable()->after('lifestyle_factors');
            $table->boolean('uses_peak_flow_meter')->default(false)->after('inhaler_technique_notes');
            $table->boolean('has_action_plan')->default(false)->after('uses_peak_flow_meter');
            $table->integer('peak_flow_baseline')->nullable()->after('has_action_plan');
            
            // Contacts d'urgence
            $table->string('emergency_contact_name')->nullable()->after('peak_flow_baseline');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            
            // Informations hospitalières
            $table->string('emergency_hospital')->nullable()->after('emergency_contact_relationship');
            $table->string('emergency_hospital_phone')->nullable()->after('emergency_hospital');
            $table->string('asthma_follow_up_hospital')->nullable()->after('emergency_hospital_phone');
            $table->string('asthma_follow_up_hospital_phone')->nullable()->after('asthma_follow_up_hospital');
            $table->text('hospital_notes')->nullable()->after('asthma_follow_up_hospital_phone');
            
            // Équipe médicale
            $table->string('attending_doctor_phone')->nullable()->after('hospital_notes');
            $table->string('asthma_specialist')->nullable()->after('attending_doctor_phone');
            $table->string('asthma_specialist_phone')->nullable()->after('asthma_specialist');
            
            // Assurance et instructions
            $table->string('insurance_number')->nullable()->after('asthma_specialist_phone');
            $table->text('special_instructions')->nullable()->after('insurance_number');
            
            // Statut patient
            $table->boolean('is_active_patient')->default(true)->after('special_instructions');
            $table->date('registration_date')->nullable()->after('is_active_patient');
            $table->boolean('phone_verified')->default(false)->after('registration_date');
            $table->timestamp('pin_created_at')->nullable()->after('phone_verified');
            $table->timestamp('last_login_at')->nullable()->after('pin_created_at');
            $table->integer('login_attempts')->default(0)->after('last_login_at');
            $table->timestamp('locked_until')->nullable()->after('login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'last_name', 'gender', 'birth_date', 'height', 'weight', 'blood_type',
                'medical_history', 'allergies', 'attending_doctor', 'current_treatments',
                'asthma_severity', 'medical_notes', 'asthma_triggers', 'current_medications',
                'dosage_instructions', 'family_history', 'lifestyle_factors',
                'inhaler_technique_notes', 'uses_peak_flow_meter', 'has_action_plan',
                'peak_flow_baseline', 'emergency_contact_name', 'emergency_contact_phone',
                'emergency_contact_relationship', 'emergency_hospital', 'emergency_hospital_phone',
                'asthma_follow_up_hospital', 'asthma_follow_up_hospital_phone', 'hospital_notes',
                'attending_doctor_phone', 'asthma_specialist', 'asthma_specialist_phone',
                'insurance_number', 'special_instructions', 'is_active_patient',
                'registration_date', 'phone_verified', 'pin_created_at', 'last_login_at',
                'login_attempts', 'locked_until'
            ]);
        });
    }
};
