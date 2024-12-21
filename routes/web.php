<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DssController;
use App\Http\Controllers\DisasterController;
use App\Http\Controllers\DisasterTypeController;
use App\Http\Controllers\MedicalCasesController;
use App\Http\Controllers\RescueTeamController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicularAccidentsController;
use App\Models\Disaster;
use App\Models\MedicalCase;
use App\Models\VehicularAccident;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'rolemanager:user'])->name('dashboard');

// Admin dashboard route
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->middleware(['auth', 'verified', 'rolemanager:admin'])
    ->name('admin');

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Medical cases for all users
    Route::get('/medical_cases', [MedicalCasesController::class, 'index'])->name('medical_cases.index');
    Route::get('/medical_cases/create', [MedicalCasesController::class, 'create'])->name('medical_cases.create');
    Route::post('/medical_cases', [MedicalCasesController::class, 'store'])->name('medical_cases.store');

    // Vehicular accidents for all users (create and view only)
    Route::get('/vehicular_accidents', [VehicularAccidentsController::class, 'index'])->name('vehicular_accidents.index');
    Route::get('/vehicular_accidents/create', [VehicularAccidentsController::class, 'create'])->name('vehicular_accidents.create');
    Route::post('/vehicular_accidents', [VehicularAccidentsController::class, 'store'])->name('vehicular_accidents.store');
    // Route::get('/yearly-vehicular-accidents', [VehicularAccidentsController::class, 'showYearlyReport'])->name('vehicular_accidents.yearly');

    // Disasters
    Route::get('/disasters', [DisasterController::class, 'index'])->name('disasters.index');
    Route::get('/disaster-data/{type}', [DisasterController::class, 'getDisasterData']);
    Route::get('/disasters/create', [DisasterController::class, 'create'])->name('disasters.create');
    Route::post('/disasters', [DisasterController::class, 'store'])->name('disasters.store');

    // Routes for Disaster Types
    Route::middleware(['auth'])->group(function () {
        // Index: List all disaster types
        Route::get('/disaster_type', [DisasterTypeController::class, 'index'])->name('disaster_type.index');

        // Create: Show form to create a new disaster type
        Route::get('/disaster_type/create', [DisasterTypeController::class, 'create'])->name('disaster_type.create');

        // Store: Save new disaster type
        Route::post('/disaster_type', [DisasterTypeController::class, 'store'])->name('disaster_type.store');

        // Edit: Show form to edit a disaster type
        Route::get('/disaster_type/{id}/edit', [DisasterTypeController::class, 'edit'])->name('disaster_type.edit');

        // Update: Save changes to a disaster type
        Route::put('/disaster_type/{id}', [DisasterTypeController::class, 'update'])->name('disaster_type.update');

        // Destroy: Delete a disaster type
        Route::delete('/disaster_type/{id}', [DisasterTypeController::class, 'destroy'])->name('disaster_type.destroy');
    });

    // Combined API route for getting accidents and medical cases by barangay
    Route::get('/api/cases', function (Request $request) {
        $barangay = $request->query('barangay');
        $month = $request->query('month', Carbon::now()->month); // Default to the current month if not provided

        if (empty($barangay)) {
            return response()->json(['error' => 'Barangay parameter is required'], 400);
        }

        // Fetch accident and medical case counts for the given barangay and month
        $accidentsCount = VehicularAccident::where('barangay', $barangay)
            ->where('is_approved', 1)
            ->whereMonth('date', $month)
            ->count();

        $medicalsCount = MedicalCase::where('barangay', $barangay)
            ->where('is_approved', 1)
            ->whereMonth('date', $month)
            ->count();

        $disastersCount = Disaster::where('barangay', $barangay)
            ->where('is_approved', 1)
            ->whereMonth('date', $month)
            ->count();

        $rescueTeams = ['Alpha', 'Bravo', 'Charlie', 'Delta'];
        $counts = [];

        foreach ($rescueTeams as $team) {
            // Medical cases count by team
            $counts[$team]['medicals_count'] = MedicalCase::where('rescue_team', $team)
                ->where('is_approved', 1)
                ->where('barangay', $barangay)
                ->whereMonth('date', $month)
                ->count();

            // Vehicular accident cases count by team
            $counts[$team]['accidents_count'] = VehicularAccident::where('rescue_team', $team)
                ->where('is_approved', 1)
                ->where('barangay', $barangay)
                ->whereMonth('date', $month)
                ->count();

            $counts[$team]['disasters_count'] = Disaster::where('rescue_team', $team)
                ->where('is_approved', 1)
                ->where('barangay', $barangay)
                ->whereMonth('date', $month)
                ->count();
        }

        // Fetch Punong Barangay and Contact Number
        $barangayInfo = DB::table('barangays')
            ->where('name', $barangay)
            ->first(['punong_barangay', 'contact_number']);

        return response()->json([
            'barangay' => $barangay,
            'accidents_count' => $accidentsCount,
            'medicals_count' => $medicalsCount,
            'disasters_count' => $disastersCount,
            'punong_barangay' => $barangayInfo->punong_barangay ?? 'Unknown',
            'contact_number' => $barangayInfo->contact_number ?? 'N/A',
            'alpha' => [
                'medicals_count' => $counts['Alpha']['medicals_count'],
                'accidents_count' => $counts['Alpha']['accidents_count'],
                'disasters_count' => $counts['Alpha']['disasters_count'],
            ],
            'bravo' => [
                'medicals_count' => $counts['Bravo']['medicals_count'],
                'accidents_count' => $counts['Bravo']['accidents_count'],
                'disasters_count' => $counts['Bravo']['disasters_count'],
            ],
            'charlie' => [
                'medicals_count' => $counts['Charlie']['medicals_count'],
                'accidents_count' => $counts['Charlie']['accidents_count'],
                'disasters_count' => $counts['Charlie']['disasters_count'],
            ],
            'delta' => [
                'medicals_count' => $counts['Delta']['medicals_count'],
                'accidents_count' => $counts['Delta']['accidents_count'],
                'disasters_count' => $counts['Delta']['disasters_count'],
            ],
        ]);
    });
});

// Admin-only routes
Route::middleware(['auth', 'rolemanager:admin'])->group(function () {
    // User management routes
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::resource('users', UserController::class)->except(['show']);

    // Medical cases admin-only actions
    Route::get('/medical_cases/{medicalCase}/edit', [MedicalCasesController::class, 'edit'])->name('medical_cases.edit');
    Route::put('/medical_cases/{medicalCase}', [MedicalCasesController::class, 'update'])->name('medical_cases.update');
    Route::delete('/medical_cases/{medicalCase}', [MedicalCasesController::class, 'destroy'])->name('medical_cases.destroy');
    Route::patch('/medical_cases/{medicalCase}/approve', [MedicalCasesController::class, 'approve'])->name('medical_cases.approve');
    Route::get('/yearly-reports', [MedicalCasesController::class, 'showYearlyReport'])->name('medical_cases.yearly');

    // Vehicular accidents admin-only actions
    Route::get('/vehicular_accidents/{vehicularAccident}/edit', [VehicularAccidentsController::class, 'edit'])->name('vehicular_accidents.edit');
    Route::put('/vehicular_accidents/{vehicularAccident}', [VehicularAccidentsController::class, 'update'])->name('vehicular_accidents.update');
    Route::delete('/vehicular_accidents/{vehicularAccident}', [VehicularAccidentsController::class, 'destroy'])->name('vehicular_accidents.destroy');
    Route::patch('/vehicular_accidents/{vehicularAccident}/approve', [VehicularAccidentsController::class, 'approve'])->name('vehicular_accidents.approve');

    // Disasters admin-only actions
    Route::patch('/disasters/{id}/approve', [DisasterController::class, 'approve'])->name('disasters.approve');
    Route::delete('/disasters/{id}', [DisasterController::class, 'destroy'])->name('disasters.destroy');
    Route::get('/disasters/{disaster}/edit', [DisasterController::class, 'edit'])->name('disasters.edit');
    Route::put('/disasters/{disaster}', [DisasterController::class, 'update'])->name('disasters.update');

    Route::get('/medical_dss', [DssController::class, 'index'])->name('dss_tools.index');
    Route::get('/vehicular_dss', [DssController::class, 'index2'])->name('dss_tools.vehicular');
    Route::get('/disaster_dss', [DssController::class, 'index3'])->name('dss_tools.disaster');
});

    // Routes for Disaster Types
    Route::middleware(['auth'])->group(function () {
        // Index: List all disaster types
        Route::get('/rescue_team', [RescueTeamController::class, 'index'])->name('rescue_team.index');

        // Create: Show form to create a new disaster type
        Route::get('/rescue_team/create', [RescueTeamController::class, 'create'])->name('rescue_team.create');

        // Store: Save new disaster type
        Route::post('/rescue_team', [RescueTeamController::class, 'store'])->name('rescue_team.store');

        // Edit: Show form to edit a disaster type
        Route::get('/rescue_team/{id}/edit', [RescueTeamController::class, 'edit'])->name('rescue_team.edit');

        // Update: Save changes to a disaster type
        Route::put('/rescue_team/{id}', [RescueTeamController::class, 'update'])->name('rescue_team.update');

        // Destroy: Delete a disaster type
        Route::delete('/rescue_team/{id}', [RescueTeamController::class, 'destroy'])->name('rescue_team.destroy');
    });


// API route
Route::get('/get-barangay-cases', [MedicalCasesController::class, 'getBarangayCases']);
Route::get('/get-barangay-details/{barangay}', [MedicalCasesController::class, 'getBarangayDetails']);

Route::get('/get-barangay-accidents', [VehicularAccidentsController::class, 'getBarangayCases']);
Route::get('/get-barangay-accidents/{barangay}', [VehicularAccidentsController::class, 'getBarangayDetails']);
Route::get('/get-barangay-accidents2/{barangay}', [VehicularAccidentsController::class, 'getBarangayDetails2']);

Route::get('/get-disaster-data', [DisasterController::class, 'getDisasterData']);
Route::get('/get-disaster-details/{disasterType}', [DisasterController::class, 'getDisasterDetails']);

Route::get('/yearly-medicals', [MedicalCasesController::class, 'getYearlyMedicals']);


// API route to get barangays with cases for the current month
Route::get('/api/cases/summary', function (Request $request) {
    $month = $request->query('month', Carbon::now()->month); // Default to the current month if not provided

    // Fetch barangays with vehicular accidents for the given month
    $accidentBarangays = VehicularAccident::where('is_approved', 1)
        ->whereMonth('date', $month)
        ->distinct()
        ->pluck('barangay');

    // Fetch barangays with medical cases for the given month
    $medicalBarangays = MedicalCase::where('is_approved', 1)
        ->whereMonth('date', $month)
        ->distinct()
        ->pluck('barangay');

    // Fetch barangays with disaster cases for the given month
    $disasterBarangays = Disaster::where('is_approved', 1)
        ->whereMonth('date', $month)
        ->distinct()
        ->pluck('barangay');

    // Combine all lists and remove duplicates
    $barangaysWithCases = $accidentBarangays
        ->merge($medicalBarangays)
        ->merge($disasterBarangays)
        ->unique();

    return response()->json([
        'month' => $month,
        'barangays' => $barangaysWithCases,
    ]);
});



require __DIR__ . '/auth.php';
