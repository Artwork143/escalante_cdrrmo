<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedicalCasesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicularAccidentsController;
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
        }

        // Fetch Punong Barangay and Contact Number
        $barangayInfo = DB::table('barangays')
            ->where('name', $barangay)
            ->first(['punong_barangay', 'contact_number']);

        return response()->json([
            'barangay' => $barangay,
            'accidents_count' => $accidentsCount,
            'medicals_count' => $medicalsCount,
            'punong_barangay' => $barangayInfo->punong_barangay ?? 'Unknown',
            'contact_number' => $barangayInfo->contact_number ?? 'N/A',
            'alpha' => [
                'medicals_count' => $counts['Alpha']['medicals_count'],
                'accidents_count' => $counts['Alpha']['accidents_count'],
            ],
            'bravo' => [
                'medicals_count' => $counts['Bravo']['medicals_count'],
                'accidents_count' => $counts['Bravo']['accidents_count'],
            ],
            'charlie' => [
                'medicals_count' => $counts['Charlie']['medicals_count'],
                'accidents_count' => $counts['Charlie']['accidents_count'],
            ],
            'delta' => [
                'medicals_count' => $counts['Delta']['medicals_count'],
                'accidents_count' => $counts['Delta']['accidents_count'],
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
});

// API route
Route::get('/get-barangay-cases', [MedicalCasesController::class, 'getBarangayCases']);
Route::get('/get-barangay-details/{barangay}', [MedicalCasesController::class, 'getBarangayDetails']);

Route::get('/get-barangay-accidents', [VehicularAccidentsController::class, 'getBarangayCases']);
Route::get('/get-barangay-accidents/{barangay}', [VehicularAccidentsController::class, 'getBarangayDetails']);

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

    // Combine both lists and remove duplicates
    $barangaysWithCases = $accidentBarangays->merge($medicalBarangays)->unique();

    return response()->json([
        'month' => $month,
        'barangays' => $barangaysWithCases,
    ]);
});


require __DIR__ . '/auth.php';
