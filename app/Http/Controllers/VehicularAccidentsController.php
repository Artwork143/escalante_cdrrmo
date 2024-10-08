<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicularAccident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicularAccidentsController extends Controller
{
    /**
     * Display a listing of vehicular accidents.
     */
    public function index(Request $request)
    {
        // Filter by month if selected
        $month = $request->input('month');
        $year = $request->input('year');

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0; // Assuming role 0 is admin

        // Retrieve accidents based on approval status and month filter
        $vehicularAccidents = VehicularAccident::when($month, function ($query, $month) {
            return $query->whereMonth('date', $month);
        })
            ->when($year, function ($query, $year) {
                return $query->whereYear('date', $year);
            })
            ->when(!$isAdmin, function ($query) {
                return $query->where('is_approved', 1); // Non-admin users only see approved accidents
            })
            ->get();

        // Total number of patients for all listed accidents (since you filtered approved accidents already)
        $totalPatients = $vehicularAccidents->where('is_approved', 1)->sum('no_of_patients');

        // Months array
        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return view('vehicular_accidents.index', [
            'vehicularAccidents' => $vehicularAccidents,
            'months' => $months,
            'totalPatients' => $totalPatients,
        ]);
    }

    /**
     * Show the form for creating a new vehicular accident.
     */
    public function create()
    {
        return view('vehicular_accidents.create');
    }

    /**
     * Store a newly created vehicular accident in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string',
            'place_of_incident' => 'required|string',
            'barangay' => 'required|string',
            'no_of_patients' => 'required|integer',
            'cause_of_incident' => 'required|string',
            'vehicles_involved' => 'required|array',  // Ensure vehicles_involved is an array
            'vehicles_involved.*' => 'string',  // Each vehicle must be a string
            'facility_name' => 'required|string',
        ]);

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0;

        // Create the vehicular accident record
        VehicularAccident::create([
            'date' => $request->date,
            'rescue_team' => $request->rescue_team,
            'place_of_incident' => $request->place_of_incident,
            'barangay' => $request->barangay,
            'no_of_patients' => $request->no_of_patients,
            'cause_of_incident' => $request->cause_of_incident,
            'vehicles_involved' => implode(', ', $request->vehicles_involved), // Convert array to string
            'facility_name' => $request->facility_name,
            'is_approved' => $isAdmin ? 1 : 0, // Automatically approve if admin
        ]);

        return redirect()->route('vehicular_accidents.index')->with('success', 'Vehicular accident created successfully!');
    }

    /**
     * Approve the specified vehicular accident.
     */
    public function approve($id)
    {
        $vehicularAccident = VehicularAccident::findOrFail($id);
        $vehicularAccident->update(['is_approved' => 1]);

        return redirect()->route('vehicular_accidents.index')->with('success', 'Vehicular accident approved successfully!');
    }

    /**
     * Show the form for editing the specified vehicular accident.
     */
    public function edit($id)
    {
        $vehicularAccident = VehicularAccident::findOrFail($id);

        return view('vehicular_accidents.edit', compact('vehicularAccident'));
    }

    /**
     * Update the specified vehicular accident in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string',
            'barangay' => 'required|string',
            'place_of_incident' => 'required|string',
            'no_of_patients' => 'required|integer',
            'cause_of_incident' => 'required|string',
            'vehicles_involved' => 'required|array',
            'vehicles_involved.*' => 'string',
            'facility_name' => 'required|string',
        ]);

        // Update the vehicular accident
        $vehicularAccident = VehicularAccident::findOrFail($id);
        $vehicularAccident->update([
            'date' => $request->date,
            'rescue_team' => $request->rescue_team,
            'place_of_incident' => $request->place_of_incident,
            'barangay' => $request->barangay,
            'no_of_patients' => $request->no_of_patients,
            'cause_of_incident' => $request->cause_of_incident,
            'vehicles_involved' => implode(', ', $request->vehicles_involved),
            'facility_name' => $request->facility_name,
        ]);

        return redirect()->route('vehicular_accidents.index')->with('success', 'Vehicular accident updated successfully!');
    }

    /**
     * Remove the specified vehicular accident from storage.
     */
    public function destroy($id)
    {
        VehicularAccident::findOrFail($id)->delete();

        return redirect()->route('vehicular_accidents.index')->with('success', 'Vehicular accident deleted successfully!');
    }

    /**
     * Get vehicular accidents by barangay.
     */
    public function getAccidentsByBarangay(Request $request)
    {
        $barangay = $request->input('barangay');

        // Retrieve and count the accidents in the specified barangay
        $accidentCount = VehicularAccident::where('barangay', $barangay)
            ->where('is_approved', 1) // Only count approved accidents
            ->count();

        return response()->json(['accidents_count' => $accidentCount]);
    }

    public function showYearlyReport()
    {
        // Fetch accident data grouped by barangay, cause_of_incident, and year
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

        return view('vehicular_accidents.yearly', [
            'yearlyAccidents' => $yearlyAccidentsFormatted
        ]);
    }

    public function getBarangayCases(Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Adjust your query to filter by the selected month and year
        $barangayAccidents = VehicularAccident::select('barangay', DB::raw('COUNT(*) as total_accidents'))
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->where('is_approved', 1)
            ->groupBy('barangay')
            ->get();

        return response()->json($barangayAccidents);
    }


    public function getBarangayDetails($barangay, Request $request)
    {
        $month = $request->input('month');
        $year = $request->input('year');

        // Fetch detailed cases for the given barangay, month, and year
        $barangayDetails = VehicularAccident::where('barangay', $barangay)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->where('is_approved', 1)
            ->get(['date', 'rescue_team', 'place_of_incident', 'no_of_patients', 'cause_of_incident', 'vehicles_involved', 'facility_name', 'barangay']);

        return response()->json($barangayDetails);
    }
}
