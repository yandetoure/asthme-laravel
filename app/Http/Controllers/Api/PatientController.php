<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $patients = User::with(['crises', 'treatments'])->activePatients()->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $patient = User::with(['crises', 'treatments', 'hospitalizations', 'exams', 'prescriptions', 'lungCapacityRecords'])
            ->find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $patient->id,
                'name' => $patient->name,
                'last_name' => $patient->last_name,
                'full_name' => $patient->full_name,
                'email' => $patient->email,
                'phone' => $patient->phone,
                'gender' => $patient->gender,
                'birth_date' => $patient->birth_date,
                'age' => $patient->age,
                'height' => $patient->height,
                'weight' => $patient->weight,
                'bmi' => $patient->bmi,
                'bmi_category' => $patient->bmi_category,
                'blood_type' => $patient->blood_type,
                'asthma_severity' => $patient->asthma_severity,
                'medical_history' => $patient->medical_history,
                'allergies' => $patient->allergies,
                'attending_doctor' => $patient->attending_doctor,
                'current_treatments' => $patient->current_treatments,
                'medical_notes' => $patient->medical_notes,
                'emergency_contact' => $patient->emergency_contact,
                'hospital_info' => $patient->hospital_info,
                'medical_team' => $patient->medical_team,
                'asthma_specialist' => $patient->asthma_specialist,
                'insurance_number' => $patient->insurance_number,
                'phone_verified' => $patient->phone_verified,
                'registration_date' => $patient->registration_date,
                'is_active_patient' => $patient->is_active_patient,
                'latest_crisis' => $patient->latest_crisis,
                'latest_lung_capacity_record' => $patient->latest_lung_capacity_record,
                'crises_count' => $patient->crises()->count(),
                'treatments_count' => $patient->treatments()->count(),
                'hospitalizations_count' => $patient->hospitalizations()->count(),
                'exams_count' => $patient->exams()->count(),
                'prescriptions_count' => $patient->prescriptions()->count(),
                'lung_capacity_records_count' => $patient->lungCapacityRecords()->count(),
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $patient = User::find($id);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient non trouvé'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'last_name' => 'nullable|string|max:255',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'phone' => 'sometimes|required|string|unique:users,phone,' . $id . '|regex:/^[0-9+\-\s()]+$/',
                'gender' => 'nullable|in:male,female,other',
                'birth_date' => 'nullable|date',
                'pin' => 'sometimes|required|string|size:4|regex:/^[0-9]+$/',
                'height' => 'nullable|numeric|min:50|max:300',
                'weight' => 'nullable|numeric|min:10|max:500',
                'blood_type' => 'nullable|string|max:10',
                'medical_history' => 'nullable|string',
                'allergies' => 'nullable|string',
                'attending_doctor' => 'nullable|string|max:255',
                'current_treatments' => 'nullable|string',
                'asthma_severity' => 'nullable|in:mild,moderate,severe',
                'medical_notes' => 'nullable|string',
                'emergency_contact_name' => 'nullable|string|max:255',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'emergency_contact_relationship' => 'nullable|string|max:100',
                'emergency_hospital' => 'nullable|string|max:255',
                'asthma_follow_up_hospital' => 'nullable|string|max:255',
                'asthma_specialist' => 'nullable|string|max:255',
                'insurance_number' => 'nullable|string|max:100',
            ], [
                'name.required' => 'Le prénom est requis.',
                'phone.required' => 'Le numéro de téléphone est requis.',
                'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
                'phone.regex' => 'Le format du numéro de téléphone est invalide.',
                'pin.required' => 'Le code PIN est requis.',
                'pin.size' => 'Le code PIN doit contenir exactement 4 chiffres.',
                'pin.regex' => 'Le code PIN ne doit contenir que des chiffres.',
                'email.unique' => 'Cette adresse email est déjà utilisée.',
                'gender.in' => 'Le genre doit être male, female ou other.',
                'asthma_severity.in' => 'La sévérité de l\'asthme doit être mild, moderate ou severe.',
            ]);

            // Hash the PIN if provided
            if (isset($validated['pin'])) {
                $validated['pin'] = Hash::make($validated['pin']);
                $validated['pin_created_at'] = now();
            }

            $patient->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Patient mis à jour avec succès',
                'data' => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'last_name' => $patient->last_name,
                    'full_name' => $patient->full_name,
                    'phone' => $patient->phone,
                    'email' => $patient->email,
                    'gender' => $patient->gender,
                    'birth_date' => $patient->birth_date,
                    'asthma_severity' => $patient->asthma_severity,
                    'updated_at' => $patient->updated_at
                ]
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
        $patient = User::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient non trouvé'
            ], 404);
        }

        // Soft delete - mark as inactive instead of deleting
        $patient->update(['is_active_patient' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Patient désactivé avec succès'
        ]);
    }

    /**
     * Search patients.
     */
    public function search(Request $request): JsonResponse
    {
        $search = $request->get('q');
        
        if (!$search) {
            return response()->json([
                'success' => false,
                'message' => 'Le terme de recherche est requis'
            ], 400);
        }

        $patients = User::search($search)
            ->activePatients()
            ->with(['crises', 'treatments'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    /**
     * Get patients by asthma severity.
     */
    public function bySeverity(Request $request): JsonResponse
    {
        $severity = $request->get('severity');
        
        if (!in_array($severity, ['mild', 'moderate', 'severe'])) {
            return response()->json([
                'success' => false,
                'message' => 'Sévérité invalide. Utilisez mild, moderate ou severe'
            ], 400);
        }

        $patients = User::byAsthmaSeverity($severity)
            ->activePatients()
            ->with(['crises', 'treatments'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    /**
     * Get patients by gender.
     */
    public function byGender(Request $request): JsonResponse
    {
        $gender = $request->get('gender');
        
        if (!in_array($gender, ['male', 'female', 'other'])) {
            return response()->json([
                'success' => false,
                'message' => 'Genre invalide. Utilisez male, female ou other'
            ], 400);
        }

        $patients = User::byGender($gender)
            ->activePatients()
            ->with(['crises', 'treatments'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    /**
     * Update patient's PIN.
     */
    public function updatePin(Request $request, string $id): JsonResponse
    {
        try {
            $patient = User::find($id);

            if (!$patient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient non trouvé'
                ], 404);
            }

            $validated = $request->validate([
                'pin' => 'required|string|size:4|regex:/^[0-9]+$/',
            ], [
                'pin.required' => 'Le code PIN est requis.',
                'pin.size' => 'Le code PIN doit contenir exactement 4 chiffres.',
                'pin.regex' => 'Le code PIN ne doit contenir que des chiffres.',
            ]);

            $patient->update([
                'pin' => Hash::make($validated['pin']),
                'pin_created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Code PIN mis à jour avec succès',
                'data' => [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'phone' => $patient->phone,
                    'pin_updated_at' => $patient->pin_created_at
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
