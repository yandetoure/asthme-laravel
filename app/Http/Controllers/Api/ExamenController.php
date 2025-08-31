<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Examen;
use Illuminate\Http\Request;
use App\Models\Hospitalisation;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $examens = Examen::with(['user', 'hospitalisation'])->get();
        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hospitalisation_id' => 'nullable|exists:hospitalisations,id',
            'type_examen_id' => 'required|exists:types_examens,id',
            'date_examen' => 'required|date',
            'date_resultat' => 'nullable|date|after_or_equal:date_examen',
            'statut' => 'required|in:programme,en_cours,termine,annule',
            'resultats' => 'nullable|string',
            'interpretation' => 'nullable|string',
            'medecin_prescripteur' => 'nullable|string|max:255',
            'technicien_realisateur' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'fichier_resultat' => 'nullable|string',
            'urgent' => 'boolean',
            'prix_facture' => 'nullable|numeric|min:0'
        ]);

        $examen = Examen::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Examen créé avec succès',
            'data' => $examen->load(['user', 'hospitalisation'])
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Examen $examen): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $examen->load(['user', 'hospitalisation', 'typeExamen'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Examen $examen): JsonResponse
    {
        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'hospitalisation_id' => 'nullable|exists:hospitalisations,id',
            'type_examen_id' => 'sometimes|required|exists:types_examens,id',
            'date_examen' => 'sometimes|required|date',
            'date_resultat' => 'nullable|date|after_or_equal:date_examen',
            'statut' => 'sometimes|required|in:programme,en_cours,termine,annule',
            'resultats' => 'nullable|string',
            'interpretation' => 'nullable|string',
            'medecin_prescripteur' => 'nullable|string|max:255',
            'technicien_realisateur' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'fichier_resultat' => 'nullable|string',
            'urgent' => 'boolean',
            'prix_facture' => 'nullable|numeric|min:0'
        ]);

        $examen->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Examen mis à jour avec succès',
            'data' => $examen->load(['user', 'hospitalisation'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Examen $examen): JsonResponse
    {
        $examen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Examen supprimé avec succès'
        ]);
    }

    /**
     * Get examens for a specific patient
     */
    public function getPatientExamens(User $user): JsonResponse
    {
        $examens = $user->examens()
            ->with('hospitalisation')
            ->orderBy('date_examen', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Get examens for a specific hospitalisation
     */
    public function getHospitalisationExamens(Hospitalisation $hospitalisation): JsonResponse
    {
        $examens = $hospitalisation->examens()
                            ->with('user')
            ->orderBy('date_examen', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Get urgent examens
     */
    public function getUrgentExamens(): JsonResponse
    {
        $examens = Examen::urgent()
            ->with(['user', 'hospitalisation'])
            ->orderBy('date_examen', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Get pending examens
     */
    public function getPendingExamens(): JsonResponse
    {
        $examens = Examen::enAttente()
            ->with(['user', 'hospitalisation'])
            ->orderBy('date_examen', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Get completed examens
     */
    public function getCompletedExamens(): JsonResponse
    {
        $examens = Examen::termine()
            ->with(['user', 'hospitalisation'])
            ->orderBy('date_resultat', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }

    /**
     * Update examen results
     */
    public function updateResults(Request $request, Examen $examen): JsonResponse
    {
        $request->validate([
            'resultats' => 'required|string',
            'interpretation' => 'nullable|string',
            'date_resultat' => 'required|date|after_or_equal:date_examen',
            'technicien_realisateur' => 'nullable|string|max:255',
            'observations' => 'nullable|string'
        ]);

        $examen->update([
            'resultats' => $request->resultats,
            'interpretation' => $request->interpretation,
            'date_resultat' => $request->date_resultat,
            'statut' => 'termine',
            'technicien_realisateur' => $request->technicien_realisateur,
            'observations' => $request->observations
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Résultats de l\'examen mis à jour avec succès',
            'data' => $examen->load(['user', 'hospitalisation', 'typeExamen'])
        ]);
    }

    /**
     * Get examens by type
     */
    public function getByType(string $type): JsonResponse
    {
        $examens = Examen::where('type_examen', $type)
            ->with(['user', 'hospitalisation'])
            ->orderBy('date_examen', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $examens
        ]);
    }
}
