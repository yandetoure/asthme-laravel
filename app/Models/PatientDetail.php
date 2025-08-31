<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'height',
        'weight',
        'blood_type',
        'gender',
        'birth_date',
        'current_medications',
        'dosage_instructions',
        'allergies',
        'medical_history',
        'family_history',
        'lifestyle_factors',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'asthma_severity',
        'asthma_triggers',
        'peak_flow_baseline',
        'inhaler_technique_notes',
        'uses_peak_flow_meter',
        'has_action_plan',
        'insurance_number',
        'primary_care_physician',
        'specialist_physician',
        'special_instructions'
    ];

    protected $casts = [
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'birth_date' => 'date',
        'uses_peak_flow_meter' => 'boolean',
        'has_action_plan' => 'boolean'
    ];

    /**
     * Get the patient that owns the details.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Calculate BMI (Body Mass Index).
     */
    public function getBmiAttribute(): ?float
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            return round($this->weight / ($heightInMeters * $heightInMeters), 2);
        }
        return null;
    }

    /**
     * Get BMI category.
     */
    public function getBmiCategoryAttribute(): ?string
    {
        $bmi = $this->bmi;
        if (!$bmi) return null;

        if ($bmi < 18.5) return 'underweight';
        if ($bmi < 25) return 'normal';
        if ($bmi < 30) return 'overweight';
        return 'obese';
    }

    /**
     * Scope a query to filter by asthma severity.
     */
    public function scopeByAsthmaSeverity($query, $severity)
    {
        return $query->where('asthma_severity', $severity);
    }

    /**
     * Scope a query to filter by blood type.
     */
    public function scopeByBloodType($query, $bloodType)
    {
        return $query->where('blood_type', $bloodType);
    }

    /**
     * Check if patient uses peak flow meter.
     */
    public function usesPeakFlowMeter(): bool
    {
        return $this->uses_peak_flow_meter;
    }

    /**
     * Check if patient has action plan.
     */
    public function hasActionPlan(): bool
    {
        return $this->has_action_plan;
    }
}
