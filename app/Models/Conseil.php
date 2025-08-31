<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conseil extends Model
{
    protected $fillable = [
        'titre',
        'contenu',
        'categorie',
        'niveau_severite',
        'actif',
        'ordre_affichage',
        'image_url',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];
}
