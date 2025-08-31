<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Symptom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'severity',
        'category',
        'common',
        'urgent',
        'first_aid_instructions',
        'icon',
        'color',
        'order',
        'active'
    ];

    protected $casts = [
        'common' => 'boolean',
        'urgent' => 'boolean',
        'active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the crises that have this symptom.
     */
    public function crises(): BelongsToMany
    {
        return $this->belongsToMany(Crisis::class, 'crisis_symptoms')
                    ->withPivot(['severity', 'notes', 'onset_time', 'resolution_time', 'resolved'])
                    ->withTimestamps();
    }

    /**
     * Scope a query to only include active symptoms.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to only include common symptoms.
     */
    public function scopeCommon($query)
    {
        return $query->where('common', true);
    }

    /**
     * Scope a query to only include urgent symptoms.
     */
    public function scopeUrgent($query)
    {
        return $query->where('urgent', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by severity.
     */
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to order by display order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Check if the symptom is urgent.
     */
    public function isUrgent(): bool
    {
        return $this->urgent;
    }

    /**
     * Check if the symptom is common.
     */
    public function isCommon(): bool
    {
        return $this->common;
    }
}
