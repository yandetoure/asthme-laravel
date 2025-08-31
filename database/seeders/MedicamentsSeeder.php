<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Medicament;

class MedicamentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicaments = [
            [
                'titre' => 'Ventoline (Salbutamol)',
                'description' => 'Bronchodilatateur à action rapide pour soulager les crises d\'asthme',
                'image' => 'ventoline.jpg',
                'categorie' => 'Bronchodilatateur',
                'forme_pharmaceutique' => 'Aérosol doseur',
                'indications' => 'Traitement des crises d\'asthme et prévention de l\'asthme d\'effort',
                'contre_indications' => 'Hypersensibilité au salbutamol ou à l\'un des excipients',
                'effets_secondaires' => 'Tremblements, tachycardie, céphalées, nervosité',
                'posologie' => '1-2 bouffées en cas de crise, maximum 8 bouffées par jour',
                'interactions' => 'Peut interagir avec les bêta-bloquants',
                'disponible' => true
            ],
            [
                'titre' => 'Symbicort (Budesonide/Formotérol)',
                'description' => 'Association corticostéroïde et bronchodilatateur pour le traitement de fond',
                'image' => 'symbicort.jpg',
                'categorie' => 'Traitement de fond',
                'forme_pharmaceutique' => 'Aérosol doseur',
                'indications' => 'Traitement de fond de l\'asthme persistant modéré à sévère',
                'contre_indications' => 'Hypersensibilité aux composants, infections respiratoires non contrôlées',
                'effets_secondaires' => 'Candidose orale, enrouement, toux, maux de gorge',
                'posologie' => '1-2 bouffées 2 fois par jour selon la sévérité',
                'interactions' => 'Interactions possibles avec les antifongiques azolés',
                'disponible' => true
            ],
            [
                'titre' => 'Flixotide (Fluticasone)',
                'description' => 'Corticostéroïde inhalé pour réduire l\'inflammation bronchique',
                'image' => 'flixotide.jpg',
                'categorie' => 'Corticostéroïde',
                'forme_pharmaceutique' => 'Aérosol doseur',
                'indications' => 'Traitement préventif de l\'asthme persistant',
                'contre_indications' => 'Hypersensibilité au fluticasone, infections respiratoires non traitées',
                'effets_secondaires' => 'Candidose orale, enrouement, toux, maux de gorge',
                'posologie' => '1-2 bouffées 1-2 fois par jour selon la posologie prescrite',
                'interactions' => 'Interactions avec les inhibiteurs du cytochrome P450',
                'disponible' => true
            ],
            [
                'titre' => 'Serevent (Salmétérol)',
                'description' => 'Bronchodilatateur à longue durée d\'action pour le contrôle de l\'asthme',
                'image' => 'serevent.jpg',
                'categorie' => 'Bronchodilatateur longue durée',
                'forme_pharmaceutique' => 'Aérosol doseur',
                'indications' => 'Traitement de fond de l\'asthme persistant modéré à sévère',
                'contre_indications' => 'Hypersensibilité au salmétérol, asthme instable',
                'effets_secondaires' => 'Tremblements, tachycardie, céphalées, nervosité',
                'posologie' => '2 bouffées 2 fois par jour',
                'interactions' => 'Interactions avec les bêta-bloquants',
                'disponible' => true
            ],
            [
                'titre' => 'Singulair (Montélukast)',
                'description' => 'Antileucotriène pour le traitement préventif de l\'asthme',
                'image' => 'singulair.jpg',
                'categorie' => 'Antileucotriène',
                'forme_pharmaceutique' => 'Comprimé',
                'indications' => 'Traitement préventif de l\'asthme persistant léger à modéré',
                'contre_indications' => 'Hypersensibilité au montélukast',
                'effets_secondaires' => 'Maux de tête, vertiges, troubles du sommeil',
                'posologie' => '1 comprimé par jour le soir',
                'interactions' => 'Interactions limitées avec d\'autres médicaments',
                'disponible' => true
            ]
        ];

        foreach ($medicaments as $medicament) {
            Medicament::create($medicament);
        }
    }
}
