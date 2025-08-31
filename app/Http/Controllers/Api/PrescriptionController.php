<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Hospitalisation;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $prescriptions = Prescription::with(['patient', 'hospitalisation', 'medicament'])->get();
        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'hospitalisation_id' => 'nullable|exists:hospitalisations,id',
            'medicament_id' => 'nullable|exists:medicaments,id',
            'type_prescription_id' => 'required|exists:types_prescriptions,id',
            'medecin_prescripteur' => 'required|string|max:255',
            'date_prescription' => 'required|date',
            'date_debut_traitement' => 'nullable|date|after_or_equal:date_prescription',
            'date_fin_traitement' => 'nullable|date|after:date_debut_traitement',
            'posologie' => 'required|string|max:255',
            'frequence' => 'required|string|max:255',
            'instructions_particulieres' => 'nullable|string',
            'statut' => 'required|in:active,terminee,annulee,suspendue',
            'raison_suspension' => 'nullable|string',
            'observations' => 'nullable|string',
            'nombre_renouvellements' => 'nullable|integer|min:0',
            'prix_facture' => 'nullable|numeric|min:0',
            'quantite' => 'nullable|integer|min:1',
            'notes_pharmacien' => 'nullable|string'
        ]);

        $prescription = Prescription::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Prescription créée avec succès',
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament', 'typePrescription'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Prescription $prescription): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament', 'typePrescription'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Prescription $prescription): JsonResponse
    {
        $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,id',
            'hospitalisation_id' => 'nullable|exists:hospitalisations,id',
            'medicament_id' => 'nullable|exists:medicaments,id',
            'type_prescription_id' => 'sometimes|required|exists:types_prescriptions,id',
            'medecin_prescripteur' => 'sometimes|required|string|max:255',
            'date_prescription' => 'sometimes|required|date',
            'date_debut_traitement' => 'nullable|date|after_or_equal:date_prescription',
            'date_fin_traitement' => 'nullable|date|after:date_debut_traitement',
            'posologie' => 'sometimes|required|string|max:255',
            'frequence' => 'sometimes|required|string|max:255',
            'instructions_particulieres' => 'nullable|string',
            'statut' => 'sometimes|required|in:active,terminee,annulee,suspendue',
            'raison_suspension' => 'nullable|string',
            'observations' => 'nullable|string',
            'nombre_renouvellements' => 'nullable|integer|min:0',
            'prix_facture' => 'nullable|numeric|min:0',
            'quantite' => 'nullable|integer|min:1',
            'notes_pharmacien' => 'nullable|string'
        ]);

        $prescription->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Prescription mise à jour avec succès',
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prescription $prescription): JsonResponse
    {
        $prescription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prescription supprimée avec succès'
        ]);
    }

    /**
     * Get prescriptions for a specific patient
     */
    public function getPatientPrescriptions(Patient $patient): JsonResponse
    {
        $prescriptions = $patient->prescriptions()
            ->with(['hospitalisation', 'medicament'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Get prescriptions for a specific hospitalisation
     */
    public function getHospitalisationPrescriptions(Hospitalisation $hospitalisation): JsonResponse
    {
        $prescriptions = $hospitalisation->prescriptions()
            ->with(['patient', 'medicament'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Get active prescriptions
     */
    public function getActivePrescriptions(): JsonResponse
    {
        $prescriptions = Prescription::active()
            ->with(['patient', 'hospitalisation', 'medicament'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Get completed prescriptions
     */
    public function getCompletedPrescriptions(): JsonResponse
    {
        $prescriptions = Prescription::terminee()
            ->with(['patient', 'hospitalisation', 'medicament'])
            ->orderBy('date_fin_traitement', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Get prescriptions by type
     */
    public function getByType(string $type): JsonResponse
    {
        $prescriptions = Prescription::byType($type)
            ->with(['patient', 'hospitalisation', 'medicament'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Get prescriptions for a specific medicament
     */
    public function getMedicamentPrescriptions(Medicament $medicament): JsonResponse
    {
        $prescriptions = $medicament->prescriptions()
            ->with(['patient', 'hospitalisation'])
            ->orderBy('date_prescription', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $prescriptions
        ]);
    }

    /**
     * Suspend a prescription
     */
    public function suspend(Request $request, Prescription $prescription): JsonResponse
    {
        $request->validate([
            'raison_suspension' => 'required|string',
            'observations' => 'nullable|string'
        ]);

        $prescription->update([
            'statut' => 'suspendue',
            'raison_suspension' => $request->raison_suspension,
            'observations' => $request->observations
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription suspendue avec succès',
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament'])
        ]);
    }

    /**
     * Reactivate a prescription
     */
    public function reactivate(Prescription $prescription): JsonResponse
    {
        $prescription->update([
            'statut' => 'active',
            'raison_suspension' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription réactivée avec succès',
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament'])
        ]);
    }

    /**
     * Terminate a prescription
     */
    public function terminate(Request $request, Prescription $prescription): JsonResponse
    {
        $request->validate([
            'date_fin_traitement' => 'required|date|after:date_debut_traitement',
            'observations' => 'nullable|string'
        ]);

        $prescription->update([
            'statut' => 'terminee',
            'date_fin_traitement' => $request->date_fin_traitement,
            'observations' => $request->observations
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Prescription terminée avec succès',
            'data' => $prescription->load(['patient', 'hospitalisation', 'medicament'])
        ]);
    }
}
