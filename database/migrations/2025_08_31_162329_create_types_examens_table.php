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
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category'); // Biology, Imaging, Functional, etc.
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('currency')->default('EUR'); // EUR, USD, etc.
            $table->integer('estimated_duration_minutes')->nullable();
            $table->text('required_preparations')->nullable();
            $table->text('contraindications')->nullable();
            $table->text('risks')->nullable();
            $table->string('laboratory')->nullable(); // Laboratory that performs the exam
            $table->string('required_equipment')->nullable();
            $table->boolean('available')->default(true);
            $table->boolean('urgent_possible')->default(false);
            $table->integer('result_delay_hours')->nullable();
            $table->text('technical_notes')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_types');
    }
};
