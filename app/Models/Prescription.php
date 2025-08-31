<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospitalisation_id',
        'medicament_id',
        'type_prescription_id',
        'medecin_prescripteur',
        'date_prescription',
        'date_debut_traitement',
        'date_fin_traitement',
        'posologie',
        'frequence',
        'instructions_particulieres',
        'statut',
        'raison_suspension',
        'observations',
        'nombre_renouvellements',
        'prix_facture',
        'quantite',
        'notes_pharmacien'
    ];

    protected $casts = [
        'date_prescription' => 'datetime',
        'date_debut_traitement' => 'datetime',
        'date_fin_traitement' => 'datetime',
        'renouvelable' => 'boolean',
        'nombre_renouvellements' => 'integer',
        'prix_facture' => 'decimal:2',
        'quantite' => 'integer',
    ];

    /**
     * Get the patient for this prescription.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the hospitalisation for this prescription.
     */
    public function hospitalisation(): BelongsTo
    {
        return $this->belongsTo(Hospitalisation::class);
    }

    /**
     * Get the medicament for this prescription.
     */
    public function medicament(): BelongsTo
    {
        return $this->belongsTo(Medicament::class);
    }

    /**
     * Get the type prescription for this prescription.
     */
    public function typePrescription(): BelongsTo
    {
        return $this->belongsTo(TypePrescription::class, 'type_prescription_id');
    }

    /**
     * Scope to get only active prescriptions
     */
    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    /**
     * Scope to get completed prescriptions
     */
    public function scopeTerminee($query)
    {
        return $query->where('statut', 'terminee');
    }

    /**
     * Scope to get prescriptions by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if prescription is active
     */
    public function isActive(): bool
    {
        return $this->statut === 'active';
    }

    /**
     * Check if prescription is renewable
     */
    public function isRenewable(): bool
    {
        return $this->renouvelable === true;
    }

    /**
     * Check if prescription is for medication
     */
    public function isMedication(): bool
    {
        return $this->type === 'medicament';
    }
}
