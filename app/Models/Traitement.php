<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Traitement extends Model
{
    protected $fillable = [
        'user_id',
        'medicament_id',
        'nom_medicament',
        'description',
        'dosage',
        'frequence',
        'type',
        'date_debut',
        'date_fin',
        'actif',
        'effets_secondaires',
        'instructions',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'actif' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function medicament(): BelongsTo
    {
        return $this->belongsTo(Medicament::class);
    }
}
