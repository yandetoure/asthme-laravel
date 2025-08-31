<?php declare(strict_types=1); 4 chiffresmplifi tout seulevec identifia

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Register a new patient with phone and PIN
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|unique:users,phone|regex:/^[0-9+\-\s()]+$/',
            'pin' => 'required|string|size:4|regex:/^[0-9]+$/',
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'gender' => 'nullable|in:male,female,other',
            'birth_date' => 'nullable|date',
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
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'phone.regex' => 'Le format du numéro de téléphone est invalide.',
            'pin.required' => 'Le code PIN est requis.',
            'pin.size' => 'Le code PIN doit contenir exactement 4 chiffres.',
            'pin.regex' => 'Le code PIN ne doit contenir que des chiffres.',
            'name.required' => 'Le prénom est requis.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'gender.in' => 'Le genre doit être male, female ou other.',
            'asthma_severity.in' => 'La sévérité de l\'asthme doit être mild, moderate ou severe.',
            'height.min' => 'La taille doit être d\'au moins 50 cm.',
            'height.max' => 'La taille ne peut pas dépasser 300 cm.',
            'weight.min' => 'Le poids doit être d\'au moins 10 kg.',
            'weight.max' => 'Le poids ne peut pas dépasser 500 kg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Créer l'utilisateur avec toutes les informations patient
        $userData = $request->only([
            'name', 'last_name', 'email', 'phone', 'gender', 'birth_date',
            'height', 'weight', 'blood_type', 'asthma_severity',
            'medical_history', 'allergies', 'attending_doctor', 'current_treatments', 'medical_notes',
            'emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship',
            'emergency_hospital', 'asthma_follow_up_hospital', 'asthma_specialist',
            'insurance_number'
        ]);

        // Données de sécurité et d'authentification
        $userData['pin'] = Hash::make($request->pin);
        $userData['phone_verified'] = false;
        $userData['pin_created_at'] = now();
        $userData['registration_date'] = now();
        $userData['is_active_patient'] = true;
        $userData['password'] = Hash::make(Str::random(16)); // Mot de passe aléatoire pour compatibilité
        $userData['login_attempts'] = 0;
        $userData['locked_until'] = null;

        // Valeurs par défaut pour les champs optionnels
        $userData['asthma_severity'] = $userData['asthma_severity'] ?? 'moderate';
        $userData['asthma_triggers'] = null;
        $userData['current_medications'] = null;
        $userData['dosage_instructions'] = null;
        $userData['family_history'] = null;
        $userData['lifestyle_factors'] = null;
        $userData['inhaler_technique_notes'] = null;
        $userData['uses_peak_flow_meter'] = false;
        $userData['has_action_plan'] = false;
        $userData['peak_flow_baseline'] = null;
        $userData['emergency_hospital_phone'] = null;
        $userData['asthma_follow_up_hospital_phone'] = null;
        $userData['hospital_notes'] = null;
        $userData['attending_doctor_phone'] = null;
        $userData['asthma_specialist_phone'] = null;
        $userData['special_instructions'] = null;

        $user = User::create($userData);

        // Générer le token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Patient enregistré avec succès',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'gender' => $user->gender,
                    'birth_date' => $user->birth_date,
                    'age' => $user->age,
                    'asthma_severity' => $user->asthma_severity,
                    'phone_verified' => $user->phone_verified,
                    'registration_date' => $user->registration_date,
                    'is_active_patient' => $user->is_active_patient
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ], 201);
    }

    /**
     * Login with phone and PIN
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/',
            'pin' => 'required|string|size:4|regex:/^[0-9]+$/'
        ], [
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.regex' => 'Le format du numéro de téléphone est invalide.',
            'pin.required' => 'Le code PIN est requis.',
            'pin.size' => 'Le code PIN doit contenir exactement 4 chiffres.',
            'pin.regex' => 'Le code PIN ne doit contenir que des chiffres.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        // Vérifier si l'utilisateur existe
        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone ou code PIN incorrect'
            ], 401);
        }

        // Vérifier si le compte est verrouillé
        if ($user->locked_until && $user->locked_until->isFuture()) {
            return response()->json([
                'success' => false,
                'message' => 'Compte temporairement verrouillé. Réessayez plus tard.',
                'locked_until' => $user->locked_until
            ], 423);
        }

        // Vérifier le PIN
        if (!Hash::check($request->pin, $user->pin)) {
            // Incrémenter le compteur de tentatives
            $user->increment('login_attempts');
            
            // Verrouiller le compte après 5 tentatives échouées
            if ($user->login_attempts >= 5) {
                $user->update([
                    'locked_until' => now()->addMinutes(30)
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Trop de tentatives échouées. Compte verrouillé pour 30 minutes.'
                ], 423);
            }

            return response()->json([
                'success' => false,
                'message' => 'Numéro de téléphone ou code PIN incorrect',
                'attempts_remaining' => 5 - $user->login_attempts
            ], 401);
        }

        // Réinitialiser les tentatives de connexion
        $user->update([
            'login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => now()
        ]);

        // Générer le token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Connexion réussie',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'last_name' => $user->last_name,
                    'full_name' => $user->full_name,
                    'phone' => $user->phone,
                    'email' => $user->email,
                    'birth_date' => $user->birth_date,
                    'age' => $user->age,
                    'asthma_severity' => $user->asthma_severity,
                    'phone_verified' => $user->phone_verified,
                    'last_login_at' => $user->last_login_at
                ],
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }

    /**
     * Change PIN
     */
    public function changePin(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'current_pin' => 'required|string|size:4|regex:/^[0-9]+$/',
            'new_pin' => 'required|string|size:4|regex:/^[0-9]+$/|different:current_pin'
        ], [
            'current_pin.required' => 'Le code PIN actuel est requis.',
            'current_pin.size' => 'Le code PIN actuel doit contenir exactement 4 chiffres.',
            'current_pin.regex' => 'Le code PIN actuel ne doit contenir que des chiffres.',
            'new_pin.required' => 'Le nouveau code PIN est requis.',
            'new_pin.size' => 'Le nouveau code PIN doit contenir exactement 4 chiffres.',
            'new_pin.regex' => 'Le nouveau code PIN ne doit contenir que des chiffres.',
            'new_pin.different' => 'Le nouveau code PIN doit être différent de l\'actuel.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Vérifier le PIN actuel
        if (!Hash::check($request->current_pin, $user->pin)) {
            return response()->json([
                'success' => false,
                'message' => 'Code PIN actuel incorrect'
            ], 401);
        }

        // Mettre à jour le PIN
        $user->update([
            'pin' => Hash::make($request->new_pin),
            'pin_created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Code PIN modifié avec succès'
        ]);
    }

    /**
     * Request PIN reset via SMS
     */
    public function requestPinReset(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|regex:/^[0-9+\-\s()]+$/|exists:users,phone'
        ], [
            'phone.required' => 'Le numéro de téléphone est requis.',
            'phone.regex' => 'Le format du numéro de téléphone est invalide.',
            'phone.exists' => 'Aucun compte trouvé avec ce numéro de téléphone.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('phone', $request->phone)->first();
        
        // Générer un nouveau PIN temporaire
        $tempPin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        
        // Ici, vous intégreriez votre service SMS pour envoyer le PIN
        // Pour l'exemple, on simule l'envoi
        // SMS::send($user->phone, "Votre nouveau code PIN temporaire est: {$tempPin}");
        
        $user->update([
            'pin' => Hash::make($tempPin),
            'pin_created_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Un nouveau code PIN a été envoyé par SMS'
        ]);
    }

    /**
     * Logout
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie'
        ]);
    }

    /**
     * Get current user profile
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'last_name' => $user->last_name,
                'full_name' => $user->full_name,
                'phone' => $user->phone,
                'email' => $user->email,
                'birth_date' => $user->birth_date,
                'age' => $user->age,
                'gender' => $user->gender,
                'height' => $user->height,
                'weight' => $user->weight,
                'bmi' => $user->bmi,
                'bmi_category' => $user->bmi_category,
                'blood_type' => $user->blood_type,
                'asthma_severity' => $user->asthma_severity,
                'asthma_triggers' => $user->asthma_triggers,
                'current_medications' => $user->current_medications,
                'dosage_instructions' => $user->dosage_instructions,
                'allergies' => $user->allergies,
                'medical_history' => $user->medical_history,
                'family_history' => $user->family_history,
                'lifestyle_factors' => $user->lifestyle_factors,

                'inhaler_technique_notes' => $user->inhaler_technique_notes,
                'uses_peak_flow_meter' => $user->uses_peak_flow_meter,
                'has_action_plan' => $user->has_action_plan,
                'emergency_contact' => $user->emergency_contact,
                'hospital_info' => $user->hospital_info,
                'medical_team' => $user->medical_team,
                'insurance_number' => $user->insurance_number,
                'special_instructions' => $user->special_instructions,
                'medical_notes' => $user->medical_notes,
                'phone_verified' => $user->phone_verified,
                'is_active_patient' => $user->is_active_patient,
                'registration_date' => $user->registration_date,
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at
            ]
        ]);
    }
}
