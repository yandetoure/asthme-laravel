<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'phone',
        'pin',
        'birth_date',
        'gender',
        'height',
        'weight',
        'blood_type',
        'medical_history',
        'allergies',
        'family_history',
        'lifestyle_factors',
        'current_medications',
        'dosage_instructions',
        'asthma_severity',
        'asthma_triggers',

        'inhaler_technique_notes',
        'uses_peak_flow_meter',
        'has_action_plan',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'emergency_hospital',
        'asthma_follow_up_hospital',
        'emergency_hospital_phone',
        'asthma_follow_up_hospital_phone',
        'hospital_notes',
        'attending_doctor',
        'attending_doctor_phone',
        'asthma_specialist',
        'asthma_specialist_phone',
        'insurance_number',
        'special_instructions',
        'medical_notes',
        'is_active_patient',
        'registration_date',
        'phone_verified',
        'pin_created_at',
        'last_login_at',
        'login_attempts',
        'locked_until',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'pin',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'uses_peak_flow_meter' => 'boolean',
        'has_action_plan' => 'boolean',
        'is_active_patient' => 'boolean',
        'registration_date' => 'date',
        'phone_verified' => 'boolean',
        'pin_created_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'login_attempts' => 'integer',
        'password' => 'hashed',
    ];

    /**
     * Get the patient's detailed information.
     */
    public function details(): HasOne
    {
        return $this->hasOne(PatientDetail::class, 'patient_id', 'id');
    }

    /**
     * Get the crises for the patient.
     */
    public function crises(): HasMany
    {
        return $this->hasMany(Crisis::class, 'patient_id', 'id');
    }

    /**
     * Get the treatments for the patient.
     */
    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class, 'patient_id', 'id');
    }

    /**
     * Get the hospitalizations for the patient.
     */
    public function hospitalizations(): HasMany
    {
        return $this->hasMany(Hospitalization::class, 'patient_id', 'id');
    }

    /**
     * Get the exams for the patient.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'patient_id', 'id');
    }

    /**
     * Get the prescriptions for the patient.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'patient_id', 'id');
    }

    /**
     * Get the lung capacity records for the patient.
     */
    public function lungCapacityRecords(): HasMany
    {
        return $this->hasMany(LungCapacityRecord::class, 'patient_id', 'id');
    }

    /**
     * Get the patient's full name.
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->name . ' ' . ($this->last_name ?? ''));
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
     * Check if the user account is locked.
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Check if the phone is verified.
     */
    public function isPhoneVerified(): bool
    {
        return $this->phone_verified;
    }

    /**
     * Get remaining login attempts.
     */
    public function getRemainingAttemptsAttribute(): int
    {
        return max(0, 5 - $this->login_attempts);
    }

    /**
     * Check if user can attempt login.
     */
    public function canAttemptLogin(): bool
    {
        return !$this->isLocked() && $this->remaining_attempts > 0;
    }

    /**
     * Lock the account.
     */
    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }

    /**
     * Unlock the account.
     */
    public function unlockAccount(): void
    {
        $this->update([
            'locked_until' => null,
            'login_attempts' => 0
        ]);
    }

    /**
     * Reset login attempts.
     */
    public function resetLoginAttempts(): void
    {
        $this->update([
            'login_attempts' => 0,
            'locked_until' => null
        ]);
    }

    /**
     * Increment login attempts.
     */
    public function incrementLoginAttempts(): void
    {
        $this->increment('login_attempts');
        
        // Auto-lock after 5 failed attempts
        if ($this->login_attempts >= 5) {
            $this->lockAccount();
        }
    }

    /**
     * Update last login time.
     */
    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
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
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Scope a query to filter active patients.
     */
    public function scopeActivePatients($query)
    {
        return $query->where('is_active_patient', true);
    }

    /**
     * Scope a query to filter by blood type.
     */
    public function scopeByBloodType($query, $bloodType)
    {
        return $query->where('blood_type', $bloodType);
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

    /**
     * Check if patient is active.
     */
    public function isActivePatient(): bool
    {
        return $this->is_active_patient;
    }

    /**
     * Get emergency contact information.
     */
    public function getEmergencyContactAttribute(): array
    {
        return [
            'name' => $this->emergency_contact_name,
            'phone' => $this->emergency_contact_phone,
            'relationship' => $this->emergency_contact_relationship
        ];
    }

    /**
     * Get hospital information.
     */
    public function getHospitalInfoAttribute(): array
    {
        return [
            'emergency' => [
                'name' => $this->emergency_hospital,
                'phone' => $this->emergency_hospital_phone
            ],
            'follow_up' => [
                'name' => $this->asthma_follow_up_hospital,
                'phone' => $this->asthma_follow_up_hospital_phone
            ],
            'notes' => $this->hospital_notes
        ];
    }

    /**
     * Get medical team information.
     */
    public function getMedicalTeamAttribute(): array
    {
        return [
            'attending_doctor' => [
                'name' => $this->attending_doctor,
                'phone' => $this->attending_doctor_phone
            ],
            'asthma_specialist' => [
                'name' => $this->asthma_specialist,
                'phone' => $this->asthma_specialist_phone
            ]
        ];
    }
}
