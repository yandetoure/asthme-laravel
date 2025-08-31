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
        Schema::create('hospitalizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crisis_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['ongoing', 'completed', 'cancelled'])->default('ongoing');
            $table->string('department')->nullable(); // Hospital department (emergency, pneumology, etc.)
            $table->string('attending_doctor')->nullable();
            $table->text('hospitalization_reason');
            $table->text('diagnosis')->nullable();
            $table->text('treatment_received')->nullable();
            $table->text('exams_performed')->nullable();
            $table->text('prescriptions')->nullable();
            $table->text('observations')->nullable();
            $table->text('complications')->nullable();
            $table->text('discharge_recommendations')->nullable();
            $table->integer('length_of_stay_days')->nullable();
            $table->enum('severity', ['mild', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->boolean('intensive_care')->default(false);
            $table->string('room_number')->nullable();
            $table->text('nursing_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospitalizations');
    }
};
