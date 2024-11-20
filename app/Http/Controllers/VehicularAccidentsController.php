<?php

namespace App\Http\Controllers;

use App\Models\VehicleDetail;
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
        // Filter by month, year, and search term if selected
        $month = $request->input('month');
        $year = $request->input('year');
        $search = $request->input('search');

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0; // Assuming role 0 is admin

        // Query to get all vehicular accidents for display in the table
        $vehicularAccidentsQuery = VehicularAccident::with('vehicleDetails') // Eager load vehicle details
            ->when($month, function ($query, $month) {
                return $query->whereMonth('date', $month);
            })
            ->when($year, function ($query, $year) {
                return $query->whereYear('date', $year);
            })
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('place_of_incident', 'like', '%' . $search . '%')
                        ->orWhere('barangay', 'like', '%' . $search . '%')
                        ->orWhere('cause_of_incident', 'like', '%' . $search . '%')
                        ->orWhere('vehicles_involved', 'like', '%' . $search . '%')
                        ->orWhere('facility_name', 'like', '%' . $search . '%');
                });
            })
            ->when(!$isAdmin, function ($query) {
                return $query->where('is_approved', 1); // Non-admin users only see approved cases
            })
            ->orderBy('is_approved', 'asc'); // Orders by status: Pending first, then Approved

        // Retrieve paginated cases for display
        $vehicularAccidents = $vehicularAccidentsQuery->paginate(5)->appends(['month' => $month, 'year' => $year, 'search' => $search]);

        // Calculate the total patients across approved cases only
        $totalPatients = $vehicularAccidentsQuery->where('is_approved', 1)->sum('no_of_patients');

        // Retrieve all cases without pagination for printing
        $allAccidentsForPrint = $vehicularAccidentsQuery->paginate($totalPatients)->appends(['month' => $month, 'year' => $year]);

        $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        return view('vehicular_accidents.index', [
            'vehicularAccidents' => $vehicularAccidents,
            'allAccidentsForPrint' => $allAccidentsForPrint,
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
            'other_cause' => 'nullable|string', // Validate "Other" input as optional
            'vehicles_involved' => 'required|array',  // Ensure vehicles_involved is an array
            'vehicles_involved.*' => 'string',  // Each vehicle must be a string
            'facility_name' => 'required|string',
        ]);

        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0;

        // Determine the cause of incident
        $causeOfIncident = $request->cause_of_incident === 'Other'
            ? $request->other_cause // Use the "Other" input value if "Other" is selected
            : $request->cause_of_incident;

        // Create the vehicular accident record
        $accident = VehicularAccident::create([
            'date' => $request->date,
            'rescue_team' => $request->rescue_team,
            'place_of_incident' => $request->place_of_incident,
            'barangay' => $request->barangay,
            'no_of_patients' => $request->no_of_patients,
            'cause_of_incident' => $causeOfIncident, // Save the appropriate cause
            'vehicles_involved' => implode(', ', $request->vehicles_involved), // Convert array to string
            'facility_name' => $request->facility_name,
            'is_approved' => $isAdmin ? 1 : 0, // Automatically approve if admin
        ]);

        // Loop through the vehicles involved and their specific details
        foreach ($request->vehicles_involved as $vehicle) {
            $vehicleDetail = null;

            // Depending on the vehicle, get the specific detail from the request
            if ($vehicle == 'Motorcycle') {
                $vehicleDetail = $request->input('motorcycle_type');
            } elseif ($vehicle == 'Car') {
                $vehicleDetail = $request->input('car_type');
            } elseif ($vehicle == 'Tricycle') {
                $vehicleDetail = $request->input('tricycle_type');
            } elseif ($vehicle == 'Van') {
                $vehicleDetail = $request->input('van_type');
            } elseif ($vehicle == 'Bus') {
                $vehicleDetail = $request->input('bus_type');
            } elseif ($vehicle == 'Truck') {
                $vehicleDetail = $request->input('truck_type');
            }

            // Store each vehicle and its details in the vehicle_details table (or related table)
            if ($vehicleDetail) {
                $accident->vehicles()->create([
                    'vehicle_type' => $vehicle,
                    'vehicle_detail' => $vehicleDetail,
                ]);
            }
        }

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
        $vehicularAccident = VehicularAccident::with('vehicleDetails')->findOrFail($id);

        // Prepare vehicle details as a key-value pair for easier access in the form
        $vehicleDetails = $vehicularAccident->vehicleDetails->pluck('vehicle_detail', 'vehicle_type')->toArray();

        return view('vehicular_accidents.edit', compact('vehicularAccident', 'vehicleDetails'));
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

            // Add validations for specific vehicle inputs
            'motorcycle_type' => 'nullable|string',
            'car_type' => 'nullable|string',
            'tricycle_type' => 'nullable|string',
            'van_type' => 'nullable|string',
            'bus_type' => 'nullable|string',
            'truck_type' => 'nullable|string',
        ]);

        // Find the vehicular accident record
        $vehicularAccident = VehicularAccident::findOrFail($id);

        // Update the main vehicular accident record
        $vehicularAccident->update([
            'date' => $request->date,
            'rescue_team' => $request->rescue_team,
            'place_of_incident' => $request->place_of_incident,
            'barangay' => $request->barangay,
            'no_of_patients' => $request->no_of_patients,
            'cause_of_incident' => $request->cause_of_incident,
            'facility_name' => $request->facility_name,
        ]);

        // Update vehicle details
        // Delete old vehicle details related to this accident
        VehicleDetail::where('vehicular_accident_id', $vehicularAccident->id)->delete();

        // Add the new vehicle details
        foreach ($request->vehicles_involved as $vehicle) {
            $specificInputKey = strtolower($vehicle) . '_type'; // e.g., 'motorcycle_type'
            $specificDetail = $request->input($specificInputKey, null); // Get specific input or null

            VehicleDetail::create([
                'vehicular_accident_id' => $vehicularAccident->id,
                'vehicle_type' => $vehicle,
                'vehicle_detail' => $specificDetail,
            ]);
        }

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
        $page = $request->input('page', 1); // Default to the first page
        $perPage = 5; // Number of items per page
        $search = $request->input('search'); // Search query

        // Fetch detailed cases for the given barangay, month, and year
        $barangayDetails = VehicularAccident::with(['vehicles']) // Load related vehicles
            ->where('barangay', $barangay)
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('date', $month);
            })
            ->when($year, function ($query) use ($year) {
                $query->whereYear('date', $year);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('place_of_incident', 'like', '%' . $search . '%');
                });
            })
            ->where('is_approved', 1)
            ->paginate($perPage, ['*'], 'page', $page);

        // Transform the data to include formatted vehicles_involved
        $barangayDetails->getCollection()->transform(function ($accident) {
            $accident->vehicles_involved = $accident->vehicles->map(function ($vehicle) {
                return "{$vehicle->vehicle_type} ({$vehicle->vehicle_detail})";
            })->join(', ');
            return $accident;
        });

        return response()->json($barangayDetails);
    }
}
