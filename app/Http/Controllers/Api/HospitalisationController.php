<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Crisis;
use Illuminate\Http\Request;
use App\Models\Hospitalisation;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class HospitalisationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $hospitalisations = Hospitalisation::with(['user', 'crisis'])->get();
        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'crisis_id' => 'required|exists:crises,id',
            'user_id' => 'required|exists:users,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'etat' => 'required|in:en_cours,terminee,annulee',
            'service' => 'nullable|string|max:255',
            'medecin_traitant' => 'nullable|string|max:255',
            'motif_hospitalisation' => 'required|string',
            'diagnostic' => 'nullable|string',
            'traitement_recu' => 'nullable|string',
            'examens_realises' => 'nullable|string',
            'prescriptions' => 'nullable|string',
            'observations' => 'nullable|string',
            'complications' => 'nullable|string',
            'recommandations_sortie' => 'nullable|string',
            'duree_sejour_jours' => 'nullable|integer|min:0',
            'gravite' => 'required|in:legere,moderee,severe,critique',
            'reanimation' => 'boolean',
            'numero_chambre' => 'nullable|string|max:50',
            'notes_infirmieres' => 'nullable|string'
        ]);

        $hospitalisation = Hospitalisation::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Hospitalisation créée avec succès',
            'data' => $hospitalisation->load(['user', 'crisis'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Hospitalisation $hospitalisation): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $hospitalisation->load(['user', 'crisis'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Hospitalisation $hospitalisation): JsonResponse
    {
        $request->validate([
            'crisis_id' => 'sometimes|required|exists:crises,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'etat' => 'sometimes|required|in:en_cours,terminee,annulee',
            'service' => 'nullable|string|max:255',
            'medecin_traitant' => 'nullable|string|max:255',
            'motif_hospitalisation' => 'sometimes|required|string',
            'diagnostic' => 'nullable|string',
            'traitement_recu' => 'nullable|string',
            'examens_realises' => 'nullable|string',
            'prescriptions' => 'nullable|string',
            'observations' => 'nullable|string',
            'complications' => 'nullable|string',
            'recommandations_sortie' => 'nullable|string',
            'duree_sejour_jours' => 'nullable|integer|min:0',
            'gravite' => 'sometimes|required|in:legere,moderee,severe,critique',
            'reanimation' => 'boolean',
            'numero_chambre' => 'nullable|string|max:50',
            'notes_infirmieres' => 'nullable|string'
        ]);

        $hospitalisation->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Hospitalisation mise à jour avec succès',
            'data' => $hospitalisation->load(['user', 'crisis'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hospitalisation $hospitalisation): JsonResponse
    {
        $hospitalisation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hospitalisation supprimée avec succès'
        ]);
    }

    /**
     * Get hospitalisations for a specific crisis
     */
    public function getCrisisHospitalisations(Crisis $crisis): JsonResponse
    {
        $hospitalisations = $crisis->hospitalisations()->with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }

    /**
     * Get hospitalisations for a specific patient
     */
    public function getPatientHospitalisations(Patient $patient): JsonResponse
    {
        $hospitalisations = $patient->hospitalisations()
            ->with('crisis')
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }

    /**
     * Get active hospitalisations
     */
    public function getActiveHospitalisations(): JsonResponse
    {
        $hospitalisations = Hospitalisation::enCours()
            ->with(['user', 'crisis'])
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }

    /**
     * Get completed hospitalisations
     */
    public function getCompletedHospitalisations(): JsonResponse
    {
        $hospitalisations = Hospitalisation::terminees()
            ->with(['user', 'crisis'])
            ->orderBy('date_fin', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }

    /**
     * Terminate an hospitalisation
     */
    public function terminate(Request $request, Hospitalisation $hospitalisation): JsonResponse
    {
        $request->validate([
            'date_fin' => 'required|date|after:date_debut',
            'recommandations_sortie' => 'nullable|string',
            'observations' => 'nullable|string'
        ]);

        $hospitalisation->update([
            'date_fin' => $request->date_fin,
            'etat' => 'terminee',
            'recommandations_sortie' => $request->recommandations_sortie,
            'observations' => $request->observations,
            'duree_sejour_jours' => $hospitalisation->date_debut->diffInDays($request->date_fin)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Hospitalisation terminée avec succès',
            'data' => $hospitalisation->load(['user', 'crisis'])
        ]);
    }

    /**
     * Get hospitalisations by severity
     */
    public function getBySeverity(string $severity): JsonResponse
    {
        $hospitalisations = Hospitalisation::where('gravite', $severity)
            ->with(['user', 'crisis'])
            ->orderBy('date_debut', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $hospitalisations
        ]);
    }
}
