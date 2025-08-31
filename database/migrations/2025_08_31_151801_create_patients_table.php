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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->date('date_naissance');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->text('antecedents')->nullable();
            $table->text('allergies')->nullable();
            $table->string('medecin_traitant')->nullable();
            $table->text('traitements_actuels')->nullable();
            $table->enum('severite_asthme', ['leger', 'modere', 'severe'])->default('modere');
            $table->text('notes_medicales')->nullable();
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
