<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicament extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'image',
        'categorie',
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
}
