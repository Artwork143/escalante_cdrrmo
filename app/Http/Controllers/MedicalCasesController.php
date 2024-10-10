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
        // Filter by month and year if selected
        $month = $request->input('month');
        $year = $request->input('year');

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0; // Assuming role 0 is admin

        // Retrieve medical cases based on approval status, month, and year
        $medicalCases = MedicalCase::when($month, function ($query, $month) {
            return $query->whereMonth('date', $month);
        })
            ->when($year, function ($query, $year) {
                return $query->whereYear('date', $year);
            })
            ->when(!$isAdmin, function ($query) {
                return $query->where('is_approved', 1); // Non-admin users only see approved medical cases
            })
            ->get();

        // Calculate total patients for approved medical cases only
        $totalPatients = $medicalCases->where('is_approved', 1)->sum('no_of_patients');

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return view('medical_cases.index', [
            'medicalCases' => $medicalCases,
            'months' => $months,
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
        // Fetch accident data grouped by barangay, cause_of_incident, and year
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

        // Restructure the data to combine causes of incidents and group by year
        $yearlyMedicalsFormatted = [];

        foreach ($yearlyMedicals as $barangay => $medicalsByBarangay) {
            $yearlyMedicalsFormatted[$barangay] = [
                'chief_complaints' => $medicalsByBarangay->pluck('chief_complaints')->unique()->implode(', '), // Combine causes
            ];

            foreach ($medicalsByBarangay as $medical) {
                $yearlyMedicalsFormatted[$barangay][$medical->year] = ($yearlyMedicalsFormatted[$barangay][$medical->year] ?? 0) + $medical->total_medicals;
            }
        }

        return view('medical_cases.yearly', [
            'yearlyMedicals' => $yearlyMedicalsFormatted
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

        // Fetch detailed cases for the given barangay, month, and year
        $barangayDetails = MedicalCase::where('barangay', $barangay)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->where('is_approved', 1)
            ->get(['date', 'rescue_team', 'place_of_incident', 'no_of_patients', 'chief_complaints', 'facility_name', 'barangay']);

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
