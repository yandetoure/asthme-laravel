<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospitalisation_id',
        'type_examen_id',
        'date_examen',
        'date_resultat',
        'statut',
        'resultats',
        'interpretation',
        'medecin_prescripteur',
        'technicien_realisateur',
        'observations',
        'fichier_resultat',
        'urgent',
        'prix_facture'
    ];

    protected $casts = [
        'date_examen' => 'datetime',
        'date_resultat' => 'datetime',
        'urgent' => 'boolean',
        'prix_facture' => 'decimal:2',
    ];

    /**
     * Get the patient for this examen.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hospitalisation for this examen.
     */
    public function hospitalisation(): BelongsTo
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    /**
     * Get the type examen for this examen.
     */
    public function typeExamen(): BelongsTo
    {
        return $this->belongsTo(TypeExamen::class, 'type_examen_id');
    }

    /**
     * Scope to get only urgent examens
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }

    /**
     * Scope to get completed examens
     */
    public function scopeTermine($query)
    {
        return $query->where('statut', 'termine');
    }

    /**
     * Scope to get pending examens
     */
    public function scopeEnAttente($query)
    {
        return $query->whereIn('statut', ['programme', 'en_cours']);
    }

    /**
     * Check if examen is completed
     */
    public function isCompleted(): bool
    {
        return $this->statut === 'termine';
    }

    /**
     * Check if examen is urgent
     */
    public function isUrgent(): bool
    {
        return $this->urgent === true;
    }
}
