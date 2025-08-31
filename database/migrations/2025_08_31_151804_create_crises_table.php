<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->datetime('debut_crise');
            $table->datetime('fin_crise')->nullable();
            $table->enum('intensite', ['leger', 'modere', 'severe'])->default('modere');
            $table->text('symptomes');
            $table->text('declencheurs')->nullable();
            $table->text('traitements_utilises')->nullable();
            $table->boolean('hospitalisation')->default(false);
            $table->text('notes')->nullable();
            $table->enum('statut', ['en_cours', 'terminee', 'annulee'])->default('en_cours');
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
