<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Traitement;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TraitementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $traitements = Traitement::with(['patient', 'medicament'])->get();
        return response()->json([
            'success' => true,
            'data' => $traitements
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicament_id' => 'required|exists:medicaments,id',
            'nom_medicament' => 'required|string|max:255',
            'description' => 'required|string',
            'dosage' => 'required|string|max:255',
            'frequence' => 'required|string|max:255',
            'type' => 'required|in:preventif,curatif,rescue',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'actif' => 'boolean',
            'effets_secondaires' => 'nullable|string',
            'instructions' => 'nullable|string'
        ]);

        $traitement = Traitement::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Traitement créé avec succès',
            'data' => $traitement->load(['patient', 'medicament'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Traitement $traitement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $traitement->load(['patient', 'medicament'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Traitement $traitement): JsonResponse
    {
        $request->validate([
            'patient_id' => 'sometimes|required|exists:patients,id',
            'medicament_id' => 'sometimes|required|exists:medicaments,id',
            'nom_medicament' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'dosage' => 'sometimes|required|string|max:255',
            'frequence' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:preventif,curatif,rescue',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date|after:date_debut',
            'actif' => 'boolean',
            'effets_secondaires' => 'nullable|string',
            'instructions' => 'nullable|string'
        ]);

        $traitement->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Traitement mis à jour avec succès',
            'data' => $traitement->load(['patient', 'medicament'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Traitement $traitement): JsonResponse
    {
        $traitement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Traitement supprimé avec succès'
        ]);
    }

    /**
     * Get traitements for a specific patient
     */
    public function getPatientTraitements(Patient $patient): JsonResponse
    {
        $traitements = $patient->traitements()->with('medicament')->get();

        return response()->json([
            'success' => true,
            'data' => $traitements
        ]);
    }

    /**
     * Get active traitements for a patient
     */
    public function getActiveTraitements(Patient $patient): JsonResponse
    {
        $traitements = $patient->traitements()
            ->where('actif', true)
            ->with('medicament')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $traitements
        ]);
    }
}
