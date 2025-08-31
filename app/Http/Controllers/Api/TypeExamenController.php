<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeExamen;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TypeExamenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $typesExamens = TypeExamen::disponible()->ordered()->withCount('examens')->get();
        return response()->json([
            'success' => true,
            'data' => $typesExamens
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:types_examens',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'required|string|max:255',
            'prix' => 'required|numeric|min:0',
            'unite_prix' => 'required|string|max:10',
            'duree_estimee_minutes' => 'nullable|integer|min:0',
            'preparations_requises' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'risques' => 'nullable|string',
            'laboratoire' => 'nullable|string|max:255',
            'equipement_requis' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'urgent_possible' => 'boolean',
            'delai_resultat_heures' => 'nullable|integer|min:0',
            'notes_techniques' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0'
        ]);

        $typeExamen = TypeExamen::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Type d\'examen créé avec succès',
            'data' => $typeExamen
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeExamen $typeExamen): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $typeExamen->load('examens')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypeExamen $typeExamen): JsonResponse
    {
        $request->validate([
            'code' => 'sometimes|required|string|max:50|unique:types_examens,code,' . $typeExamen->id,
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'sometimes|required|string|max:255',
            'prix' => 'sometimes|required|numeric|min:0',
            'unite_prix' => 'sometimes|required|string|max:10',
            'duree_estimee_minutes' => 'nullable|integer|min:0',
            'preparations_requises' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'risques' => 'nullable|string',
            'laboratoire' => 'nullable|string|max:255',
            'equipement_requis' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'urgent_possible' => 'boolean',
            'delai_resultat_heures' => 'nullable|integer|min:0',
            'notes_techniques' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0'
        ]);

        $typeExamen->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Type d\'examen mis à jour avec succès',
            'data' => $typeExamen
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeExamen $typeExamen): JsonResponse
    {
        // Vérifier s'il y a des examens de ce type
        if ($typeExamen->examens()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce type d\'examen car il est utilisé dans des examens'
            ], 422);
        }

        $typeExamen->delete();

        return response()->json([
            'success' => true,
            'message' => 'Type d\'examen supprimé avec succès'
        ]);
    }

    /**
     * Get types by category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $typesExamens = TypeExamen::byCategory($category)
            ->disponible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $typesExamens
        ]);
    }

    /**
     * Get urgent possible types
     */
    public function getUrgentPossible(): JsonResponse
    {
        $typesExamens = TypeExamen::where('urgent_possible', true)
            ->disponible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $typesExamens
        ]);
    }

    /**
     * Search types by name or code
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        
        $typesExamens = TypeExamen::where(function($q) use ($query) {
            $q->where('nom', 'like', "%{$query}%")
              ->orWhere('code', 'like', "%{$query}%")
              ->orWhere('categorie', 'like', "%{$query}%");
        })->disponible()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $typesExamens
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = TypeExamen::disponible()
            ->distinct()
            ->pluck('categorie')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
