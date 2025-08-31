<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Conseil;

class ConseilsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $conseils = [
            [
                'titre' => 'Évitez les déclencheurs courants',
                'contenu' => 'Identifiez et évitez les facteurs qui déclenchent vos crises d\'asthme : poussière, pollen, fumée, parfums forts, etc.',
                'categorie' => 'prevention',
                'niveau_severite' => 'tous',
                'ordre_affichage' => 1,
            ],
            [
                'titre' => 'Utilisez correctement votre inhalateur',
                'contenu' => 'Apprenez la technique correcte pour utiliser votre inhalateur. Consultez votre médecin pour une démonstration.',
                'categorie' => 'medicaments',
                'niveau_severite' => 'tous',
                'ordre_affichage' => 2,
            ],
            [
                'titre' => 'Surveillez votre respiration',
                'contenu' => 'Utilisez un débitmètre de pointe pour surveiller votre fonction respiratoire quotidiennement.',
                'categorie' => 'prevention',
                'niveau_severite' => 'modere',
                'ordre_affichage' => 3,
            ],
            [
                'titre' => 'Plan d\'action en cas de crise',
                'contenu' => 'Ayez toujours votre plan d\'action écrit avec les numéros d\'urgence et les médicaments à prendre.',
                'categorie' => 'gestion_crise',
                'niveau_severite' => 'severe',
                'ordre_affichage' => 4,
            ],
            [
                'titre' => 'Exercice physique adapté',
                'contenu' => 'Pratiquez des exercices physiques adaptés à votre condition. Échauffez-vous bien avant l\'effort.',
                'categorie' => 'lifestyle',
                'niveau_severite' => 'tous',
                'ordre_affichage' => 5,
            ],
            [
                'titre' => 'En cas d\'urgence',
                'contenu' => 'Si vous avez des difficultés respiratoires sévères, appelez immédiatement les secours (15 ou 112).',
                'categorie' => 'urgence',
                'niveau_severite' => 'severe',
                'ordre_affichage' => 6,
            ],
        ];

        foreach ($conseils as $conseil) {
            Conseil::create($conseil);
        }
    }
}
