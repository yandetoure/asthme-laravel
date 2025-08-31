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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospitalization_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('exam_type_id')->constrained()->onDelete('cascade');
            $table->dateTime('exam_date');
            $table->dateTime('result_date')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled'])->default('scheduled');
            $table->text('results')->nullable();
            $table->text('interpretation')->nullable();
            $table->string('prescribing_doctor')->nullable();
            $table->string('performing_technician')->nullable();
            $table->text('observations')->nullable();
            $table->string('result_file')->nullable(); // To store PDF/image files
            $table->boolean('urgent')->default(false);
            $table->decimal('billed_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
