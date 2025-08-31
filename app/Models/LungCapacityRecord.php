<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LungCapacityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'exam_id',
        'measurement_date',
        'measurement_type',
        'performed_by',
        'notes',
        'fev1',
        'fev1_predicted',
        'fev1_percentage',
        'fvc',
        'fvc_predicted',
        'fvc_percentage',
        'fev1_fvc_ratio',
        'peak_flow',
        'peak_flow_predicted',
        'peak_flow_percentage',
        'fef25_75',
        'fef25_75_predicted',
        'fef25_75_percentage',
        'tlc',
        'rv',
        'dlco',
        'test_condition',
        'test_quality_score',
        'test_acceptable',
        'test_quality_notes',
        'temperature',
        'humidity',
        'altitude',
        'environmental_notes',
        'interpretation',
        'interpretation_notes',
        'recommendations',
        'status',
        'requires_follow_up',
        'follow_up_date'
    ];

    protected $casts = [
        'measurement_date' => 'datetime',
        'fev1' => 'decimal:2',
        'fev1_predicted' => 'decimal:2',
        'fev1_percentage' => 'decimal:2',
        'fvc' => 'decimal:2',
        'fvc_predicted' => 'decimal:2',
        'fvc_percentage' => 'decimal:2',
        'fev1_fvc_ratio' => 'decimal:3',
        'peak_flow' => 'decimal:2',
        'peak_flow_predicted' => 'decimal:2',
        'peak_flow_percentage' => 'decimal:2',
        'fef25_75' => 'decimal:2',
        'fef25_75_predicted' => 'decimal:2',
        'fef25_75_percentage' => 'decimal:2',
        'tlc' => 'decimal:2',
        'rv' => 'decimal:2',
        'dlco' => 'decimal:2',
        'test_quality_score' => 'integer',
        'test_acceptable' => 'boolean',
        'temperature' => 'decimal:1',
        'humidity' => 'decimal:2',
        'altitude' => 'decimal:1',
        'requires_follow_up' => 'boolean',
        'follow_up_date' => 'date'
    ];

    /**
     * Get the patient that owns the record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the exam associated with the record.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Scope a query to filter by measurement type.
     */
    public function scopeByMeasurementType($query, $type)
    {
        return $query->where('measurement_type', $type);
    }

    /**
     * Scope a query to filter by test condition.
     */
    public function scopeByTestCondition($query, $condition)
    {
        return $query->where('test_condition', $condition);
    }

    /**
     * Scope a query to filter by interpretation.
     */
    public function scopeByInterpretation($query, $interpretation)
    {
        return $query->where('interpretation', $interpretation);
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to get recent records.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('measurement_date', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to get records requiring follow-up.
     */
    public function scopeRequiringFollowUp($query)
    {
        return $query->where('requires_follow_up', true);
    }

    /**
     * Check if the test is acceptable.
     */
    public function isTestAcceptable(): bool
    {
        return $this->test_acceptable;
    }

    /**
     * Check if follow-up is required.
     */
    public function requiresFollowUp(): bool
    {
        return $this->requires_follow_up;
    }

    /**
     * Get FEV1 severity level.
     */
    public function getFev1SeverityAttribute(): ?string
    {
        if (!$this->fev1_percentage) return null;

        if ($this->fev1_percentage >= 80) return 'normal';
        if ($this->fev1_percentage >= 60) return 'mild';
        if ($this->fev1_percentage >= 40) return 'moderate';
        return 'severe';
    }

    /**
     * Get peak flow severity level.
     */
    public function getPeakFlowSeverityAttribute(): ?string
    {
        if (!$this->peak_flow_percentage) return null;

        if ($this->peak_flow_percentage >= 80) return 'normal';
        if ($this->peak_flow_percentage >= 60) return 'mild';
        if ($this->peak_flow_percentage >= 40) return 'moderate';
        return 'severe';
    }

    /**
     * Check if bronchodilator response is significant.
     */
    public function hasSignificantBronchodilatorResponse(): bool
    {
        // Logique pour déterminer si la réponse au bronchodilatateur est significative
        // FEV1 amélioration > 12% et 200ml
        return false; // À implémenter selon les critères médicaux
    }
}
