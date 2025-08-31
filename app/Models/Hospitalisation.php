<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hospitalisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'crisis_id',
        'user_id',
        'date_debut',
        'date_fin',
        'etat',
        'service',
        'medecin_traitant',
        'motif_hospitalisation',
        'diagnostic',
        'traitement_recu',
        'examens_realises',
        'prescriptions',
        'observations',
        'complications',
        'recommandations_sortie',
        'duree_sejour_jours',
        'gravite',
        'reanimation',
        'numero_chambre',
        'notes_infirmieres'
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'reanimation' => 'boolean',
        'duree_sejour_jours' => 'integer',
    ];

    /**
     * Get the crisis for this hospitalisation.
     */
    public function crisis(): BelongsTo
    {
        return $this->belongsTo(Crisis::class);
    }

    /**
     * Get the patient for this hospitalisation.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the examens for this hospitalisation.
     */
    public function examens(): HasMany
    {
        return $this->hasMany(Examen::class);
    }

    /**
     * Get the prescriptions for this hospitalisation.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Scope to get only active hospitalisations
     */
    public function scopeEnCours($query)
    {
        return $query->where('etat', 'en_cours');
    }

    /**
     * Scope to get completed hospitalisations
     */
    public function scopeTerminees($query)
    {
        return $query->where('etat', 'terminee');
    }

    /**
     * Calculate duration of stay
     */
    public function getDureeSejourAttribute()
    {
        if ($this->date_fin && $this->date_debut) {
            return $this->date_debut->diffInDays($this->date_fin);
        }
        return null;
    }

    /**
     * Check if hospitalisation is active
     */
    public function isActive(): bool
    {
        return $this->etat === 'en_cours';
    }

    /**
     * Check if hospitalisation is in reanimation
     */
    public function isReanimation(): bool
    {
        return $this->reanimation === true;
    }
}
