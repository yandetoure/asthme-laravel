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
        Schema::create('prescription_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // Medication, Care, Exam, etc.
            $table->enum('type', ['medication', 'exam', 'care', 'other'])->default('medication');
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->string('currency')->default('EUR');
            $table->string('unit_measure')->nullable(); // Tablet, bottle, session, etc.
            $table->text('standard_dosage')->nullable();
            $table->text('standard_frequency')->nullable();
            $table->text('standard_instructions')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('side_effects')->nullable();
            $table->text('interactions')->nullable();
            $table->boolean('renewable')->default(false);
            $table->integer('treatment_duration_days')->nullable();
            $table->string('supplier')->nullable();
            $table->boolean('available')->default(true);
            $table->boolean('prescription_required')->default(true);
            $table->text('pharmacy_notes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_types');
    }
};
