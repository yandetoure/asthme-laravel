<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Crisis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'start_date',
        'end_date',
        'intensity',
        'triggers',
        'treatments_used',
        'hospitalization',
        'notes',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'hospitalization' => 'boolean'
    ];

    /**
     * Get the patient that owns the crisis.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the symptoms associated with the crisis.
     */
    public function symptoms(): BelongsToMany
    {
        return $this->belongsToMany(Symptom::class, 'crisis_symptoms')
                    ->withPivot(['severity', 'notes', 'onset_time', 'resolution_time', 'resolved'])
                    ->withTimestamps();
    }

    /**
     * Get the hospitalizations for the crisis.
     */
    public function hospitalizations(): HasMany
    {
        return $this->hasMany(Hospitalization::class);
    }

    /**
     * Scope a query to only include ongoing crises.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope a query to only include completed crises.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to filter by intensity.
     */
    public function scopeByIntensity($query, $intensity)
    {
        return $query->where('intensity', $intensity);
    }

    /**
     * Check if the crisis is ongoing.
     */
    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    /**
     * Check if the crisis is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if hospitalization was required.
     */
    public function requiredHospitalization(): bool
    {
        return $this->hospitalization;
    }
}
