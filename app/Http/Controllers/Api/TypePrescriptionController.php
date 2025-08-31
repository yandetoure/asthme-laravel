<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypePrescription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TypePrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $typesPrescriptions = TypePrescription::disponible()->ordered()->withCount('prescriptions')->get();
        return response()->json([
            'success' => true,
            'data' => $typesPrescriptions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:types_prescriptions',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'required|string|max:255',
            'type' => 'required|in:medicament,examen,soin,autre',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite_prix' => 'required|string|max:10',
            'unite_mesure' => 'nullable|string|max:100',
            'posologie_standard' => 'nullable|string',
            'frequence_standard' => 'nullable|string',
            'instructions_standard' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'interactions' => 'nullable|string',
            'renouvelable' => 'boolean',
            'duree_traitement_jours' => 'nullable|integer|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'ordonnance_requise' => 'boolean',
            'notes_pharmacie' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0'
        ]);

        $typePrescription = TypePrescription::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Type de prescription créé avec succès',
            'data' => $typePrescription
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypePrescription $typePrescription): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $typePrescription->load('prescriptions')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TypePrescription $typePrescription): JsonResponse
    {
        $request->validate([
            'code' => 'sometimes|required|string|max:50|unique:types_prescriptions,code,' . $typePrescription->id,
            'nom' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:medicament,examen,soin,autre',
            'prix_unitaire' => 'sometimes|required|numeric|min:0',
            'unite_prix' => 'sometimes|required|string|max:10',
            'unite_mesure' => 'nullable|string|max:100',
            'posologie_standard' => 'nullable|string',
            'frequence_standard' => 'nullable|string',
            'instructions_standard' => 'nullable|string',
            'contre_indications' => 'nullable|string',
            'effets_secondaires' => 'nullable|string',
            'interactions' => 'nullable|string',
            'renouvelable' => 'boolean',
            'duree_traitement_jours' => 'nullable|integer|min:0',
            'fournisseur' => 'nullable|string|max:255',
            'disponible' => 'boolean',
            'ordonnance_requise' => 'boolean',
            'notes_pharmacie' => 'nullable|string',
            'ordre' => 'nullable|integer|min:0'
        ]);

        $typePrescription->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Type de prescription mis à jour avec succès',
            'data' => $typePrescription
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypePrescription $typePrescription): JsonResponse
    {
        // Vérifier s'il y a des prescriptions de ce type
        if ($typePrescription->prescriptions()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer ce type de prescription car il est utilisé dans des prescriptions'
            ], 422);
        }

        $typePrescription->delete();

        return response()->json([
            'success' => true,
            'message' => 'Type de prescription supprimé avec succès'
        ]);
    }

    /**
     * Get types by category
     */
    public function getByCategory(string $category): JsonResponse
    {
        $typesPrescriptions = TypePrescription::byCategory($category)
            ->disponible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $typesPrescriptions
        ]);
    }

    /**
     * Get types by type
     */
    public function getByType(string $type): JsonResponse
    {
        $typesPrescriptions = TypePrescription::byType($type)
            ->disponible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $typesPrescriptions
        ]);
    }

    /**
     * Get renewable types
     */
    public function getRenewable(): JsonResponse
    {
        $typesPrescriptions = TypePrescription::where('renouvelable', true)
            ->disponible()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $typesPrescriptions
        ]);
    }

    /**
     * Search types by name or code
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        
        $typesPrescriptions = TypePrescription::where(function($q) use ($query) {
            $q->where('nom', 'like', "%{$query}%")
              ->orWhere('code', 'like', "%{$query}%")
              ->orWhere('categorie', 'like', "%{$query}%");
        })->disponible()->ordered()->get();

        return response()->json([
            'success' => true,
            'data' => $typesPrescriptions
        ]);
    }

    /**
     * Get all categories
     */
    public function getCategories(): JsonResponse
    {
        $categories = TypePrescription::disponible()
            ->distinct()
            ->pluck('categorie')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get all types
     */
    public function getTypes(): JsonResponse
    {
        $types = TypePrescription::disponible()
            ->distinct()
            ->pluck('type')
            ->sort()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $types
        ]);
    }
}
