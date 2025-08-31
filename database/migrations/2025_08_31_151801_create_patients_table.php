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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->date('birth_date');
            $table->string('email')->unique();
            $table->string('phone');
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->string('attending_doctor')->nullable();
            $table->text('current_treatments')->nullable();
            $table->enum('asthma_severity', ['mild', 'moderate', 'severe'])->default('moderate');
            $table->text('medical_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
