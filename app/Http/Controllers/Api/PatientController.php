<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $patients = Patient::with(['crises', 'traitements'])->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'date_naissance' => 'required|date',
                'email' => 'required|email|unique:patients,email',
                'telephone' => 'required|string|max:20',
                'antecedents' => 'nullable|string',
                'allergies' => 'nullable|string',
                'medecin_traitant' => 'nullable|string|max:255',
                'traitements_actuels' => 'nullable|string',
                'severite_asthme' => 'required|in:leger,modere,severe',
                'notes_medicales' => 'nullable|string',
            ]);

            $patient = Patient::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patient créé avec succès',
                'data' => $patient
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $patient = Patient::with(['crises', 'traitements'])->find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $patient = Patient::find($id);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient non trouvé'
                ], 404);
            }

            $validated = $request->validate([
                'nom' => 'sometimes|required|string|max:255',
                'prenom' => 'sometimes|required|string|max:255',
                'date_naissance' => 'sometimes|required|date',
                'email' => 'sometimes|required|email|unique:patients,email,' . $id,
                'telephone' => 'sometimes|required|string|max:20',
                'antecedents' => 'nullable|string',
                'allergies' => 'nullable|string',
                'medecin_traitant' => 'nullable|string|max:255',
                'traitements_actuels' => 'nullable|string',
                'severite_asthme' => 'sometimes|required|in:leger,modere,severe',
                'notes_medicales' => 'nullable|string',
            ]);

            $patient->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patient mis à jour avec succès',
                'data' => $patient
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient non trouvé'
            ], 404);
        }

        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient supprimé avec succès'
        ]);
    }
}
