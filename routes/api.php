<?php declare(strict_types=1); 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;

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

// Routes publiques (sans authentification)
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/request-pin-reset', [AuthController::class, 'requestPinReset']);

// Routes protégées (avec authentification)
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/change-pin', [AuthController::class, 'changePin']);
    Route::get('/auth/profile', [AuthController::class, 'profile']);
    
    // Gestion des patients (pour les médecins/admins)
    Route::prefix('patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);
        Route::get('/search', [PatientController::class, 'search']);
        Route::get('/by-severity', [PatientController::class, 'bySeverity']);
        Route::get('/by-gender', [PatientController::class, 'byGender']);
        Route::get('/{id}', [PatientController::class, 'show']);
        Route::put('/{id}', [PatientController::class, 'update']);
        Route::put('/{id}/pin', [PatientController::class, 'updatePin']);
        Route::delete('/{id}', [PatientController::class, 'destroy']);
    });
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
