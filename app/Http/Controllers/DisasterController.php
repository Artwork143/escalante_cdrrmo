<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disaster;
use App\Models\DisasterType;
use App\Models\RescueTeam;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisasterController extends Controller
{
    // Index method to load filtered disaster cases
    public function index(Request $request)
    {
        // Fetch all disaster types
        $disasterTypes = DisasterType::all();

        $isAdmin = Auth::user()->role === 0;  // Assuming 0 means admin

        // Get filters from the request
        $selectedType = $request->query('type');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $search = $request->query('search');

        $disastersQuery = Disaster::query();

        // Filter by disaster type
        if ($selectedType) {
            $disastersQuery->where('type', $selectedType);
        }

        // Filter by date range
        if ($startDate) {
            $disastersQuery->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $disastersQuery->whereDate('date', '<=', $endDate);
        }

        // Search functionality
        if ($search) {
            $disastersQuery->where(function ($query) use ($search) {
                $query->where('place_of_incident', 'like', "%{$search}%")
                    ->orWhere('rescue_team', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('barangay', 'like', "%{$search}%")
                    ->orWhere('affected_infrastructure', 'like', "%{$search}%")
                    ->orWhere('casualties', 'like', "%{$search}%")
                    ->orWhere('triggering_event', 'like', "%{$search}%")
                    ->orWhere('nature_of_encounter', 'like', "%{$search}%");
            });
        }

        $disastersQuery->when(!$isAdmin, function ($query) {
            return $query->where('is_approved', 1); // Non-admin users only see approved cases
        })
            ->orderBy('is_approved', 'asc');

        // Pagination
        $disasters = $disastersQuery->paginate(5)->appends([
            'type' => $selectedType,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'search' => $search
        ]);

        $totalCases = $disastersQuery->where('is_approved', 1)->count();

        // Retrieve all cases without pagination for printing
        $disastersForPrint = $disastersQuery->paginate($totalCases)->appends([
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);

        return view('disasters.index', compact('disasterTypes', 'disasters', 'disastersForPrint', 'totalCases'));
    }

    // Destroy a disaster case
    public function destroy($id)
    {
        // Find the disaster record by ID or fail
        $disaster = Disaster::findOrFail($id);

        // Capture the type of the disaster before deleting
        $type = $disaster->type;

        // Delete the disaster record
        $disaster->delete();

        // Redirect to the specific table using the disaster type
        return redirect()->route('disasters.index', ['type' => $type])
            ->with('success', "Disaster case of type '{$type}' deleted successfully.");
    }


    // Approve a disaster case
    public function approve($id)
    {
        $disaster = Disaster::findOrFail($id);
        $disaster->is_approved = true;
        $disaster->save();

        return redirect()->route('disasters.index')->with('success', 'Disaster case approved successfully.');
    }

    // Show form to create a new disaster case
    public function create()
    {
        $disasterTypes = DisasterType::all();
        $rescueTeams = RescueTeam::all();
        return view('disasters.create', compact('rescueTeams', 'disasterTypes'));
    }

    // Store a new disaster case
    public function store(Request $request)
    {
        // Validate the base inputs
        $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string|max:255',
            'other_rescue_team' => 'nullable|string|max:255|unique:rescue_teams,team_name',
            'place_of_incident' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'other_type' => 'nullable|string|max:255', // Validate the custom disaster type
            'current_water_level' => 'nullable|string',
            'water_level_trend' => 'nullable|string',
            'intensity_level' => 'nullable|string',
            'aftershocks' => 'nullable|string',
            'eruption_type' => 'nullable|string',
            'eruption_intensity' => 'nullable|string',
            'involved_parties' => 'nullable|string',
            'police_unit' => 'nullable|string|max:255',
            'rebel_group' => 'nullable|string|max:255',
            'triggering_event' => 'nullable|string',
            'nature_of_encounter' => 'nullable|string',
            'duration' => 'nullable|string',
        ]);

        // Determine the disaster type
        $disasterType = $request->type === 'other' ? $request->other_type : $request->type;

        // If "Others" is selected, save the custom type to the disaster_type table
        if ($request->type === 'other' && $request->other_type) {
            DB::table('disaster_type')->insertOrIgnore([
                'type_name' => $request->other_type
            ]);
        }

        $rescueTeamName = $request['rescue_team'];

        // Check if the "Other" option was selected
        if ($rescueTeamName === 'other') {
            // Validate the new rescue team name
            $rescueTeamName = $request['other_rescue_team'];

            // Insert the new rescue team into the database
            RescueTeam::create([
                'team_name' => $rescueTeamName,
            ]);
        }

        // Initialize casualties data
        $formattedCasualties = [];
        $affectedInfrastructures = [];

        // Process general casualties data
        if ($request->has('casualties_types')) {
            foreach ($request->casualties_types as $type) {
                $countKey = $type . '_count';
                $count = $request->input($countKey);

                if (is_numeric($count) && $count > 0) {
                    $formattedKey = ucfirst(str_replace('_', ' ', $type)); // Replace underscores and capitalize
                    $formattedCasualties[] = "$formattedKey: $count";
                }
            }
        }

        // Process rebel-specific casualties data if type is Rebel Encounter
        if ($disasterType === 'Rebel Encounter' && $request->has('rebel_casualties_types')) {
            foreach ($request->rebel_casualties_types as $type) {
                $countKey = $type . '_count';
                $count = $request->input($countKey);

                if (is_numeric($count) && $count > 0) {
                    $formattedKey = ucfirst(str_replace('_', ' ', $type)); // Replace underscores and capitalize
                    $formattedCasualties[] = "$formattedKey: $count";
                }
            }
        }

        // Process affected infrastructure
        if ($request->has('infrastructure_types')) {
            foreach ($request->infrastructure_types as $infrastructure) {
                $conditionKey = $infrastructure . '_condition';
                $condition = $request->input($conditionKey);

                if ($infrastructure === 'buildings' && $request->has('buildings_types')) {
                    foreach ($request->buildings_types as $buildingType) {
                        $buildingConditionKey = $buildingType . '_condition';
                        $buildingCondition = $request->input($buildingConditionKey);

                        if ($buildingCondition) {
                            $affectedInfrastructures[] = ucfirst($buildingType) . ': ' . ucfirst($buildingCondition);
                        }
                    }
                } elseif ($condition) {
                    $affectedInfrastructures[] = ucfirst($infrastructure) . ': ' . ucfirst($condition);
                }
            }
        }

        // Pre-process data
        $data = $request->all();
        $data['type'] = $disasterType; // Use the determined disaster type
        $data['rescue_team'] = $rescueTeamName;
        $data['is_approved'] = Auth::user()->role === 0; // Set approval based on user role
        $data['barangay'] = ucwords(strtolower($request->barangay)); // Format barangay name
        $data['casualties'] = implode(', ', $formattedCasualties); // Store casualties as formatted string
        $data['affected_infrastructure'] = implode(', ', $affectedInfrastructures); // Store infrastructure as formatted string

        // Handle Rebel Encounter specific logic
        if ($disasterType === 'Rebel Encounter') {
            $data['involved_parties'] =
                ($request->police_unit ?? 'N/A') .
                ' and ' . ($request->rebel_group ?? 'N/A');
        }

        // Save disaster case to database
        Disaster::create($data);

        return redirect()->route('disasters.index', ['type' => $disasterType])
            ->with('success', 'Disaster case created successfully.');
    }




    // Fetch disaster data dynamically (optional for AJAX requests)
    // public function getDisasterData($type)
    // {
    //     $validTypes = ['Flood', 'Earthquake', 'Volcanic Eruption', 'Rebel Encounter'];
    //     if (!in_array($type, $validTypes)) {
    //         return response()->json(['error' => 'Invalid disaster type.'], 400);
    //     }

    //     $disasters = Disaster::where('type', $type)->get();

    //     return response()->json($disasters);
    // }

    public function getDisastersByBarangay(Request $request)
    {
        $barangay = $request->input('barangay');

        // Retrieve and count the accidents in the specified barangay
        $disasterCount = Disaster::where('barangay', $barangay)
            ->where('is_approved', 1) // Only count approved accidents
            ->count();

        return response()->json(['disasters_count' => $disasterCount]);
    }

    public function getDisasterData(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Example: Get disasters data between the given date range
        $disasters = Disaster::select('type', DB::raw('COUNT(*) as total_casualties'))
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->groupBy('type')
            ->orderBy('is_approved', 'asc')
            ->get();

        return response()->json($disasters);
    }

    public function getDisasterDetails($disasterType, Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $page = $request->input('page', 1); // Default to the first page
        $perPage = 5; // Number of items per page
        $search = $request->input('search'); // Search query

        // Fetch paginated disaster details
        $details = Disaster::where('type', $disasterType)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('barangay', 'like', '%' . $search . '%')
                        ->orWhere('affected_infrastructure', 'like', '%' . $search . '%');
                });
            })
            ->paginate($perPage, ['*'], 'page', $page);

            return response()->json($details);
    }

    public function getDisasterDetails2($disasterType, Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $page = $request->input('page', 1); // Default to the first page
        $search = $request->input('search'); // Search query

        $barangayCount = Disaster:: // Load related vehicles
        where('type', $disasterType)
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        })
        ->where('is_approved', 1)->count();

        // Fetch paginated disaster details
        $detailsPrint = Disaster::where('type', $disasterType)
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('rescue_team', 'like', '%' . $search . '%')
                        ->orWhere('barangay', 'like', '%' . $search . '%')
                        ->orWhere('affected_infrastructure', 'like', '%' . $search . '%');
                });
            })
            ->paginate($barangayCount, ['*'], 'page', $page);

            return response()->json($detailsPrint);
    }


    public function edit(Disaster $disaster)
    {
        $rescueTeams = RescueTeam::all();
        $disasterTypes = DisasterType::all();
        return view('disasters.edit', compact('disaster', 'rescueTeams', 'disasterTypes'));
    }

    public function update(Request $request, Disaster $disaster)
    {
        $validatedData = $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string',
            'place_of_incident' => 'required|string',
            'city' => 'required|string',
            'barangay' => 'required|string',
            'type' => 'required|string',
            'affected_infrastructure' => 'nullable|string',
            'casualties' => 'nullable|string',
            'current_water_level' => 'nullable|string',
            'water_level_trend' => 'nullable|string',
            'intensity_level' => 'nullable|string',
            'aftershocks' => 'nullable|string',
            'eruption_type' => 'nullable|string',
            'eruption_intensity' => 'nullable|string',
            'involved_parties' => 'nullable|string',
            'triggering_event' => 'nullable|string',
            'nature_of_encounter' => 'nullable|string',
            'duration' => 'nullable|string',
        ]);

        $disaster->update($validatedData);

        return redirect()->route('disasters.index')->with('success', 'Disaster updated successfully!');
    }
}
