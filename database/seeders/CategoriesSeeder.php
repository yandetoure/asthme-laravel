<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Inhalateurs',
                'description' => 'Médicaments administrés par inhalation pour traiter l\'asthme',
                'icone' => 'inhaler',
                'couleur' => '#4CAF50',
                'ordre' => 1,
                'actif' => true
            ],
            [
                'nom' => 'Turbuhaler',
                'description' => 'Dispositifs d\'inhalation à poudre sèche pour le traitement de l\'asthme',
                'icone' => 'turbine',
                'couleur' => '#2196F3',
                'ordre' => 2,
                'actif' => true
            ],
            [
                'nom' => 'Sirop',
                'description' => 'Médicaments sous forme liquide pour le traitement de l\'asthme',
                'icone' => 'medicine-bottle',
                'couleur' => '#FF9800',
                'ordre' => 3,
                'actif' => true
            ],
            [
                'nom' => 'Comprimés Corticoïdes',
                'description' => 'Corticostéroïdes oraux pour le traitement de l\'asthme sévère',
                'icone' => 'pill',
                'couleur' => '#9C27B0',
                'ordre' => 4,
                'actif' => true
            ],
            [
                'nom' => 'Bronchodilatateurs',
                'description' => 'Médicaments qui dilatent les bronches pour soulager les symptômes',
                'icone' => 'lungs',
                'couleur' => '#F44336',
                'ordre' => 5,
                'actif' => true
            ],
            [
                'nom' => 'Antileucotriènes',
                'description' => 'Médicaments qui bloquent les leucotriènes pour prévenir l\'inflammation',
                'icone' => 'shield',
                'couleur' => '#607D8B',
                'ordre' => 6,
                'actif' => true
            ],
            [
                'nom' => 'Traitement de fond',
                'description' => 'Médicaments utilisés quotidiennement pour contrôler l\'asthme',
                'icone' => 'calendar',
                'couleur' => '#795548',
                'ordre' => 7,
                'actif' => true
            ],
            [
                'nom' => 'Traitement de secours',
                'description' => 'Médicaments utilisés en cas de crise d\'asthme',
                'icone' => 'emergency',
                'couleur' => '#E91E63',
                'ordre' => 8,
                'actif' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
