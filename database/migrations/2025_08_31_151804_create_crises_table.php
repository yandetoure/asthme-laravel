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
        Schema::create('crises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date')->nullable();
            $table->enum('intensity', ['mild', 'moderate', 'severe'])->default('moderate');
            $table->text('triggers')->nullable();
            $table->text('treatments_used')->nullable();
            $table->boolean('hospitalization')->default(false);
            $table->text('notes')->nullable();
            $table->enum('status', ['ongoing', 'completed', 'cancelled'])->default('ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crises');
    }
};
