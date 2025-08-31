<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'date_naissance',
        'email',
        'telephone',
        'antecedents',
        'allergies',
        'medecin_traitant',
        'traitements_actuels',
        'severite_asthme',
        'notes_medicales',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    public function crises(): HasMany
    {
        return $this->hasMany(Crisis::class);
    }

    public function traitements(): HasMany
    {
        return $this->hasMany(Traitement::class);
    }

    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
