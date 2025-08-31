<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medicament;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MedicamentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $medicaments = Medicament::with('categorie')->get();
        return response()->json([
            'success' => true,
            'data' => $medicaments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string',
            'categorie_id' => 'required|exists:categories,id',
            'forme_pharmaceutique' => 'nullable|string|max:255',
            'indications' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'posologie' => 'nullable|string',
            'interactions' => 'nullable|string',
            'disponible' => 'boolean'
        ]);

        $medicament = Medicament::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Médicament créé avec succès',
            'data' => $medicament
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicament $medicament): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $medicament->load('categorie')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medicament $medicament): JsonResponse
    {
        $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable|string',
            'categorie_id' => 'sometimes|required|exists:categories,id',
            'forme_pharmaceutique' => 'nullable|string|max:255',
            'indications' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'posologie' => 'nullable|string',
            'interactions' => 'nullable|string',
            'disponible' => 'boolean'
        ]);

        $medicament->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Médicament mis à jour avec succès',
            'data' => $medicament
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicament $medicament): JsonResponse
    {
        $medicament->delete();

        return response()->json([
            'success' => true,
            'message' => 'Médicament supprimé avec succès'
        ]);
    }

    /**
     * Get medicaments by category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $medicaments = Medicament::whereHas('categorie', function($query) use ($category) {
            $query->where('nom', $category);
        })->where('disponible', true)->with('categorie')->get();

        return response()->json([
            'success' => true,
            'data' => $medicaments
        ]);
    }

    /**
     * Search medicaments
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');

        $medicaments = Medicament::where(function($q) use ($query) {
            $q->where('titre', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%")
              ->orWhereHas('categorie', function($subQuery) use ($query) {
                  $subQuery->where('nom', 'like', "%{$query}%");
              });
        })->where('disponible', true)->with('categorie')->get();

        return response()->json([
            'success' => true,
            'data' => $medicaments
        ]);
    }
}
