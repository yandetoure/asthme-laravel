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
        Schema::create('crisis_symptoms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crisis_id')->constrained()->onDelete('cascade');
            $table->foreignId('symptom_id')->constrained()->onDelete('cascade');
            $table->enum('severity', ['mild', 'moderate', 'severe', 'critical'])->default('moderate');
            $table->text('notes')->nullable();
            $table->dateTime('onset_time')->nullable();
            $table->dateTime('resolution_time')->nullable();
            $table->boolean('resolved')->default(false);
            $table->timestamps();
            
            // Index unique pour éviter les doublons
            $table->unique(['crisis_id', 'symptom_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crisis_symptoms');
    }
};
