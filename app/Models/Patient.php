<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_name',
        'first_name',
        'birth_date',
        'email',
        'phone',
        'medical_history',
        'allergies',
        'attending_doctor',
        'current_treatments',
        'asthma_severity',
        'medical_notes'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    /**
     * Get the patient's detailed information.
     */
    public function details(): HasOne
    {
        return $this->hasOne(PatientDetail::class);
    }

    /**
     * Get the crises for the patient.
     */
    public function crises(): HasMany
    {
        return $this->hasMany(Crisis::class);
    }

    /**
     * Get the treatments for the patient.
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    /**
     * Get the hospitalizations for the patient.
     */
    public function hospitalizations(): HasMany
    {
        return $this->hasMany(Hospitalization::class);
    }

    /**
     * Get the exams for the patient.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get the prescriptions for the patient.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class);
    }

    /**
     * Get the lung capacity records for the patient.
     */
    public function lungCapacityRecords(): HasMany
    {
        return $this->hasMany(LungCapacityRecord::class);
    }

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get the patient's age.
     */
    public function getAgeAttribute(): ?int
    {
        if ($this->birth_date) {
            return $this->birth_date->age;
        }
        return null;
    }

    /**
     * Scope a query to filter by asthma severity.
     */
    public function scopeByAsthmaSeverity($query, $severity)
    {
        return $query->where('asthma_severity', $severity);
    }

    /**
     * Scope a query to search patients by name or email.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    /**
     * Get the latest lung capacity record.
     */
    public function getLatestLungCapacityRecordAttribute()
    {
        return $this->lungCapacityRecords()->latest('measurement_date')->first();
    }

    /**
     * Get the latest crisis.
     */
    public function getLatestCrisisAttribute()
    {
        return $this->crises()->latest('start_date')->first();
    }
}
