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
        Schema::create('conseils', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            $table->enum('categorie', ['prevention', 'gestion_crise', 'lifestyle', 'medicaments', 'urgence'])->default('prevention');
            $table->enum('niveau_severite', ['tous', 'leger', 'modere', 'severe'])->default('tous');
            $table->boolean('actif')->default(true);
            $table->integer('ordre_affichage')->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conseils');
    }
};
