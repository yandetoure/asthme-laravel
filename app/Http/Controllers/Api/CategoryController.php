<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::active()->ordered()->with('medicaments')->get();
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:255',
            'couleur' => 'nullable|string|max:50',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Catégorie créée avec succès',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $category->load('medicaments')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $request->validate([
            'nom' => 'sometimes|required|string|max:255|unique:categories,nom,' . $category->id,
            'description' => 'nullable|string',
            'icone' => 'nullable|string|max:255',
            'couleur' => 'nullable|string|max:50',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'boolean'
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Catégorie mise à jour avec succès',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        // Vérifier s'il y a des médicaments dans cette catégorie
        if ($category->medicaments()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer cette catégorie car elle contient des médicaments'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Catégorie supprimée avec succès'
        ]);
    }

    /**
     * Get medicaments by category
     */
    public function getMedicaments(Category $category): JsonResponse
    {
        $medicaments = $category->medicaments()->where('disponible', true)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'medicaments' => $medicaments
            ]
        ]);
    }

    /**
     * Get all categories with medicament count
     */
    public function getWithCount(): JsonResponse
    {
        $categories = Category::active()
            ->ordered()
            ->withCount('medicaments')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
