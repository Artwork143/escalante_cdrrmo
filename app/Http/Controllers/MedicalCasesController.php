<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalCase;
use App\Models\VehicularAccident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalCasesController extends Controller
{
    /**
     * Display a listing of the medical cases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get filter inputs
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0; // Assuming role 0 is admin

        // Query to get all medical cases for display in the table
        $medicalCasesQuery = MedicalCase::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('date', [$startDate, $endDate]);
        })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('place_of_incident', 'like', '%' . $search . '%')
                        ->orWhere('barangay', 'like', '%' . $search . '%')
                        ->orWhere('chief_complaints', 'like', '%' . $search . '%')
                        ->orWhere('facility_name', 'like', '%' . $search . '%');
                });
            })
            ->when(!$isAdmin, function ($query) {
                return $query->where('is_approved', 1); // Non-admin users only see approved cases
            })
            ->orderBy('is_approved', 'asc'); // Orders by status: Pending first, then Approved

        // Retrieve paginated cases for display
        $medicalCases = $medicalCasesQuery->paginate(5)->appends([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'search' => $search
        ]);

        // Calculate the total patients across approved cases only
        $totalPatients = $medicalCasesQuery->where('is_approved', 1)->sum('no_of_patients');

        // Retrieve all cases without pagination for printing
        $allMedicalCasesForPrint = $medicalCasesQuery->paginate($totalPatients)->appends([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        return view('medical_cases.index', [
            'medicalCases' => $medicalCases,
            'allMedicalCasesForPrint' => $allMedicalCasesForPrint,
            'totalPatients' => $totalPatients,
        ]);
    }

    /**
     * Show the form for creating a new medical case.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medical_cases.create');
    }

    /**
     * Store a newly created medical case in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string|max:255',
            'place_of_incident' => 'required|string|max:255',
            'barangay' => 'required|string',
            'no_of_patients' => 'required|integer',
            'chief_complaints' => 'nullable|string',
            'facility_name' => 'required|string|max:255',
        ]);

        // Set approval based on the user role
        $validated['is_approved'] = Auth::user()->role === 0 ? true : false;

        // Create a new medical case
        MedicalCase::create($validated);

        $message = Auth::user()->role === 0
            ? 'Medical case created successfully.'
            : 'Medical case submitted for admin approval.';

        return redirect()->route('medical_cases.index')->with('success', $message);
    }

    /**
     * Show the form for editing the specified medical case (Admins only).
     *
     * @param  \App\Models\MedicalCase  $medicalCase
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalCase $medicalCase)
    {
        return view('medical_cases.edit', compact('medicalCase'));
    }

    /**
     * Update the specified medical case in the database (Admins only).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicalCase  $medicalCase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalCase $medicalCase)
    {
        // Validate the request
        $validated = $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string|max:255',
            'place_of_incident' => 'required|string|max:255',
            'barangay' => 'required|string',
            'no_of_patients' => 'required|integer',
            'chief_complaints' => 'nullable|string',
            'facility_name' => 'required|string|max:255',
        ]);

        // Update the medical case
        $medicalCase->update($validated);

        return redirect()->route('medical_cases.index')->with('success', 'Medical case updated successfully.');
    }

    /**
     * Remove the specified medical case from the database (Admins only).
     *
     * @param  \App\Models\MedicalCase  $medicalCase
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalCase $medicalCase)
    {
        $medicalCase->delete();

        return redirect()->route('medical_cases.index')->with('success', 'Medical case deleted successfully.');
    }


    public function approve(MedicalCase $medicalCase)
    {
        // Mark the case as approved
        $medicalCase->update(['is_approved' => true]);

        return redirect()->route('medical_cases.index')->with('success', 'Medical case approved successfully.');
    }

    public function getMedicalsByBarangay(Request $request)
    {
        $barangay = $request->input('barangay');

        // Retrieve and count the medicals in the specified barangay
        $medicalCount = MedicalCase::where('barangay', $barangay)
            ->where('is_approved', 1) // Only count approved medicals
            ->count();

        return response()->json(['medicals_count' => $medicalCount]);
    }

    public function showYearlyReport()
    {
        // Fetch vehicular accident data grouped by barangay, cause_of_incident, and year
        $yearlyAccidents = VehicularAccident::select(
            'barangay',
            'cause_of_incident',
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as total_accidents')
        )
            ->where('is_approved', 1)
            ->groupBy('barangay', 'cause_of_incident', 'year')
            ->get()
            ->groupBy('barangay');

        // Restructure the data to combine causes of incidents and group by year
        $yearlyAccidentsFormatted = [];
        foreach ($yearlyAccidents as $barangay => $accidentsByBarangay) {
            $yearlyAccidentsFormatted[$barangay] = [
                'causes_of_incident' => $accidentsByBarangay->pluck('cause_of_incident')->unique()->implode(', '), // Combine causes
            ];
            foreach ($accidentsByBarangay as $accident) {
                $yearlyAccidentsFormatted[$barangay][$accident->year] = ($yearlyAccidentsFormatted[$barangay][$accident->year] ?? 0) + $accident->total_accidents;
            }
        }

        // Fetch medical cases data grouped by barangay, chief_complaints, and year
        $yearlyMedicals = MedicalCase::select(
            'barangay',
            'chief_complaints',
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as total_medicals')
        )
            ->where('is_approved', 1)
            ->groupBy('barangay', 'chief_complaints', 'year')
            ->get()
            ->groupBy('barangay');

        // Restructure the data to combine chief complaints and group by year
        $yearlyMedicalsFormatted = [];
        foreach ($yearlyMedicals as $barangay => $medicalsByBarangay) {
            $yearlyMedicalsFormatted[$barangay] = [
                'chief_complaints' => $medicalsByBarangay->pluck('chief_complaints')->unique()->implode(', '), // Combine complaints
            ];
            foreach ($medicalsByBarangay as $medical) {
                $yearlyMedicalsFormatted[$barangay][$medical->year] = ($yearlyMedicalsFormatted[$barangay][$medical->year] ?? 0) + $medical->total_medicals;
            }
        }

        // Initialize the combined response data
        $yearlyResponses = [];

        // Combine the yearly accidents and yearly medicals
        foreach ($yearlyAccidentsFormatted as $barangay => $accidents) {
            $yearlyResponses[$barangay] = $accidents; // Start with accidents data
            $yearlyResponses[$barangay]['total'] = 0; // Initialize total

            // Add yearly medicals to the responses
            if (isset($yearlyMedicalsFormatted[$barangay])) {
                foreach ($yearlyMedicalsFormatted[$barangay] as $year => $total_medicals) {
                    // Ensure that we are summing integers
                    $yearlyResponses[$barangay][$year] = ($yearlyResponses[$barangay][$year] ?? 0) + (int)$total_medicals;

                    // Update total for the barangay
                    $yearlyResponses[$barangay]['total'] += (int)$total_medicals; // Ensure this is an integer
                }
            }
        }

        // Add remaining medicals for barangays not in accidents
        foreach ($yearlyMedicalsFormatted as $barangay => $medicals) {
            if (!isset($yearlyResponses[$barangay])) {
                $yearlyResponses[$barangay] = $medicals; // Start with medicals data
                $yearlyResponses[$barangay]['total'] = array_sum(array_map('intval', $medicals)); // Convert to integers for sum
            }
        }

        // Pass both variables to the view
        return view('medical_cases.yearly', [
            'yearlyAccidents' => $yearlyAccidentsFormatted,
            'yearlyMedicals' => $yearlyMedicalsFormatted,
            'yearlyResponses' => $yearlyResponses, // Pass combined data to the view
        ]);
    }




    public function getBarangayCases(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Adjust your query to filter by the selected month and year
        $barangayCases = MedicalCase::select('barangay', DB::raw('COUNT(*) as total_cases'))
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->where('is_approved', 1)
            ->groupBy('barangay')
            ->get();

        return response()->json($barangayCases);
    }


    public function getBarangayDetails($barangay, Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');
        $page = $request->input('page', 1); // Default to the first page
        $perPage = 10; // Number of items per page
        $search = $request->input('search'); // Search query

        // Fetch paginated cases for the given barangay, month, and year
        $barangayDetails = MedicalCase::where('barangay', $barangay)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('place_of_incident', 'like', '%' . $search . '%')
                        ->orWhere('chief_complaints', 'like', '%' . $search . '%');
                });
            })
            ->where('is_approved', 1)
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($barangayDetails);
    }


    public function getYearlyMedicals()
    {
        // Fetch total approved medical cases per year
        $yearlyMedicals = MedicalCase::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as total_medicals')
        )
            ->where('is_approved', 1)
            ->groupBy('year')
            ->get();

        // Fetch total vehicular accident cases per year
        $vehicularAccidents = VehicularAccident::select(
            DB::raw('YEAR(date) as year'),
            DB::raw('COUNT(*) as vehicular_accidents')
        )
            ->where('is_approved', 1)
            ->groupBy('year')
            ->get();

        // Prepare the data for the front end
        $formattedMedicals = [];

        foreach ($yearlyMedicals as $case) {
            $year = $case->year;
            $totalMedicals = $case->total_medicals;

            // Find vehicular accident cases for the same year
            $vehicularAccident = $vehicularAccidents->firstWhere('year', $year);
            $accidentsCount = $vehicularAccident ? $vehicularAccident->vehicular_accidents : 0;

            // Calculate the sum of both
            $sum = $totalMedicals + $accidentsCount;

            // Add data to the array
            $formattedMedicals[] = [
                'year' => $year,
                'total_medicals' => $totalMedicals,
                'vehicular_accidents' => $accidentsCount,
                'total_sum' => $sum,
            ];
        }

        // Generate a list of years for the front end (optional)
        $years = range(2020, now()->year);

        // Return the formatted data as a JSON response
        return response()->json([
            'yearlyMedicals' => $formattedMedicals,
            'years' => $years,
        ]);
    }
}
