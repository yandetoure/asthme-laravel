<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypePrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'description',
        'categorie',
        'type',
        'prix_unitaire',
        'unite_prix',
        'unite_mesure',
        'posologie_standard',
        'frequence_standard',
        'instructions_standard',
        'contre_indications',
        'effets_secondaires',
        'interactions',
        'renouvelable',
        'duree_traitement_jours',
        'fournisseur',
        'disponible',
        'ordonnance_requise',
        'notes_pharmacie',
        'ordre'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'renouvelable' => 'boolean',
        'disponible' => 'boolean',
        'ordonnance_requise' => 'boolean',
        'duree_traitement_jours' => 'integer',
        'ordre' => 'integer',
    ];

    /**
     * Get the prescriptions for this type.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'type_prescription_id');
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
     * Scope to get types by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
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
        return number_format($this->prix_unitaire, 2) . ' ' . $this->unite_prix;
    }

    /**
     * Check if prescription is renewable
     */
    public function isRenewable(): bool
    {
        return $this->renouvelable === true;
    }

    /**
     * Check if prescription requires prescription
     */
    public function requiresPrescription(): bool
    {
        return $this->ordonnance_requise === true;
    }
}
