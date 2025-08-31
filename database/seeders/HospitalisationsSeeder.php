<?php declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hospitalisation;
use App\Models\Crisis;
use App\Models\Patient;
use Carbon\Carbon;

class HospitalisationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un patient et une crise existants
        $patient = Patient::first();
        $crisis = Crisis::first();

        if (!$patient || !$crisis) {
            return; // Pas de données à créer si pas de patient ou crise
        }

        $hospitalisations = [
            [
                'crisis_id' => $crisis->id,
                'patient_id' => $patient->id,
                'date_debut' => Carbon::now()->subDays(5),
                'date_fin' => Carbon::now()->subDays(2),
                'etat' => 'terminee',
                'service' => 'Urgences',
                'medecin_traitant' => 'Dr. Martin',
                'motif_hospitalisation' => 'Crise d\'asthme sévère avec détresse respiratoire',
                'diagnostic' => 'Exacerbation d\'asthme bronchique sévère',
                'traitement_recu' => 'Corticoïdes IV, bronchodilatateurs, oxygénothérapie',
                'examens_realises' => 'Gaz du sang, radiographie thorax, spirométrie',
                'prescriptions' => 'Ventoline 2 bouffées 4x/jour, Symbicort 2 bouffées 2x/jour',
                'observations' => 'Amélioration progressive sous traitement, sortie possible après 3 jours',
                'complications' => 'Aucune',
                'recommandations_sortie' => 'Suivi pneumologue dans la semaine, éviter les facteurs déclenchants',
                'duree_sejour_jours' => 3,
                'gravite' => 'severe',
                'reanimation' => false,
                'numero_chambre' => 'A12',
                'notes_infirmieres' => 'Patient coopératif, bonne observance du traitement'
            ],
            [
                'crisis_id' => $crisis->id,
                'patient_id' => $patient->id,
                'date_debut' => Carbon::now()->subMonths(2),
                'date_fin' => Carbon::now()->subMonths(2)->addDays(1),
                'etat' => 'terminee',
                'service' => 'Pneumologie',
                'medecin_traitant' => 'Dr. Dubois',
                'motif_hospitalisation' => 'Asthme d\'effort persistant',
                'diagnostic' => 'Asthme d\'effort mal contrôlé',
                'traitement_recu' => 'Ajustement du traitement de fond, éducation thérapeutique',
                'examens_realises' => 'Test d\'effort, spirométrie, allergologie',
                'prescriptions' => 'Modification du traitement de fond, Ventoline avant effort',
                'observations' => 'Bonne réponse au traitement ajusté',
                'complications' => 'Aucune',
                'recommandations_sortie' => 'Reprise activité physique progressive, suivi régulier',
                'duree_sejour_jours' => 1,
                'gravite' => 'moderee',
                'reanimation' => false,
                'numero_chambre' => 'B8',
                'notes_infirmieres' => 'Patient motivé pour l\'éducation thérapeutique'
            ],
            [
                'crisis_id' => $crisis->id,
                'patient_id' => $patient->id,
                'date_debut' => Carbon::now()->subYear(),
                'date_fin' => Carbon::now()->subYear()->addDays(5),
                'etat' => 'terminee',
                'service' => 'Réanimation',
                'medecin_traitant' => 'Dr. Moreau',
                'motif_hospitalisation' => 'Crise d\'asthme critique avec insuffisance respiratoire',
                'diagnostic' => 'Status asthmatique avec acidose respiratoire',
                'traitement_recu' => 'Intubation, ventilation mécanique, corticoïdes IV, bronchodilatateurs',
                'examens_realises' => 'Gaz du sang répétés, scanner thorax, échographie cardiaque',
                'prescriptions' => 'Traitement de fond renforcé, suivi pneumologue strict',
                'observations' => 'Évolution favorable après 48h de réanimation',
                'complications' => 'Pneumonie nosocomiale traitée',
                'recommandations_sortie' => 'Suivi très rapproché, éviter absolument les facteurs déclenchants',
                'duree_sejour_jours' => 5,
                'gravite' => 'critique',
                'reanimation' => true,
                'numero_chambre' => 'Réa-3',
                'notes_infirmieres' => 'Surveillance continue, patient sédaté pendant 48h'
            ]
        ];

        foreach ($hospitalisations as $hospitalisation) {
            Hospitalisation::create($hospitalisation);
        }
    }
}
