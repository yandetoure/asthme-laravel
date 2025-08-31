<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeExamen extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'description',
        'categorie',
        'prix',
        'unite_prix',
        'duree_estimee_minutes',
        'preparations_requises',
        'contre_indications',
        'risques',
        'laboratoire',
        'equipement_requis',
        'disponible',
        'urgent_possible',
        'delai_resultat_heures',
        'notes_techniques',
        'ordre'
    ];

    protected $casts = [
        'prix' => 'decimal:2',
        'disponible' => 'boolean',
        'urgent_possible' => 'boolean',
        'duree_estimee_minutes' => 'integer',
        'delai_resultat_heures' => 'integer',
        'ordre' => 'integer',
    ];

    /**
     * Get the examens for this type.
     */
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class, 'type_examen_id');
    }

    /**
     * Scope to get only available types
     */
    public function scopeDisponible($query)
    {
        return $query->where('disponible', true);
    }

    /**
     * Scope to get types by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('categorie', $category);
    }

    /**
     * Scope to order by ordre
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('ordre', 'asc');
    }

    /**
     * Get formatted price
     */
    public function getPrixFormateAttribute()
    {
        return number_format($this->prix, 2) . ' ' . $this->unite_prix;
    }

    /**
     * Check if urgent is possible
     */
    public function isUrgentPossible(): bool
    {
        return $this->urgent_possible === true;
    }
}
