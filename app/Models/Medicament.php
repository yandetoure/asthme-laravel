<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'image',
        'categorie_id',
        'forme_pharmaceutique',
        'indications',
        'contre_indications',
        'effets_secondaires',
        'posologie',
        'interactions',
        'disponible'
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /**
     * Get the traitements for this medicament.
     */
    public function traitements()
    {
        return $this->hasMany(Traitement::class);
    }

    /**
     * Get the prescriptions for this medicament.
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the category for this medicament.
     */
    public function categorie()
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }
}
