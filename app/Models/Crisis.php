<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Crisis extends Model
{
    protected $fillable = [
        'patient_id',
        'debut_crise',
        'fin_crise',
        'intensite',
        'symptomes',
        'declencheurs',
        'traitements_utilises',
        'hospitalisation',
        'notes',
        'statut',
    ];

    protected $casts = [
        'debut_crise' => 'datetime',
        'fin_crise' => 'datetime',
        'hospitalisation' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
