<?php declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\CrisisController;
use App\Http\Controllers\Api\TraitementController;
use App\Http\Controllers\Api\ConseilController;
use App\Http\Controllers\Api\MedicamentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Routes pour les patients
Route::apiResource('patients', PatientController::class);

// Routes pour les crises
Route::apiResource('crises', CrisisController::class);
Route::get('patients/{patient}/crises', [CrisisController::class, 'getPatientCrises']);

// Routes pour les traitements
Route::apiResource('traitements', TraitementController::class);
Route::get('patients/{patient}/traitements', [TraitementController::class, 'getPatientTraitements']);
Route::get('patients/{patient}/traitements/actifs', [TraitementController::class, 'getActiveTraitements']);

// Routes pour les conseils
Route::apiResource('conseils', ConseilController::class);
Route::get('conseils/categorie/{categorie}', [ConseilController::class, 'getByCategory']);
Route::get('conseils/severite/{severite}', [ConseilController::class, 'getBySeverity']);

// Routes pour les médicaments
Route::apiResource('medicaments', MedicamentController::class);
Route::get('medicaments/categorie/{category}', [MedicamentController::class, 'getByCategory']);
Route::get('medicaments/search', [MedicamentController::class, 'search']);
