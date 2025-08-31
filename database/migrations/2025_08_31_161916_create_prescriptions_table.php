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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospitalization_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('medication_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('prescription_type_id')->constrained()->onDelete('cascade');
            $table->string('prescribing_doctor');
            $table->dateTime('prescription_date');
            $table->dateTime('treatment_start_date')->nullable();
            $table->dateTime('treatment_end_date')->nullable();
            $table->string('dosage'); // Specific dosage for this patient
            $table->string('frequency'); // Specific frequency for this patient
            $table->text('special_instructions')->nullable(); // Specific instructions
            $table->enum('status', ['active', 'completed', 'cancelled', 'suspended'])->default('active');
            $table->text('suspension_reason')->nullable();
            $table->text('observations')->nullable();
            $table->integer('renewal_count')->default(0);
            $table->decimal('billed_price', 10, 2)->nullable();
            $table->integer('quantity')->default(1);
            $table->text('pharmacist_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
