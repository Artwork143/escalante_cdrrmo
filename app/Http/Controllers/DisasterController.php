<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disaster;
use Illuminate\Support\Facades\Auth;

class DisasterController extends Controller
{
    // Index method to load filtered disaster cases
    public function index(Request $request)
    {
        // Check if the user is an admin
        $isAdmin = Auth::user()->role === 0;

        // Hardcoded disaster types for dropdown
        $disasterTypes = ['Flood', 'Earthquake', 'Volcanic Eruption', 'Rebel Encounter'];

        // Get the selected disaster type from query parameters (or default to the first type)
        $selectedType = $request->query('type', $disasterTypes[0]);

        // Validate if the selected type is valid
        if (!in_array($selectedType, $disasterTypes)) {
            return redirect()->route('disasters.index')->withErrors(['Invalid disaster type selected.']);
        }

        // Retrieve disasters filtered by type
        $disasters = Disaster::where('type', $selectedType)
            ->when(!$isAdmin, function ($query) {
                return $query->where('is_approved', 1); // Non-admin users only see approved cases
            })
            ->get();

        return view('disasters.index', compact('disasters', 'disasterTypes', 'selectedType'));
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
        return view('disasters.create');
    }

    // Store a new disaster case
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string|max:255',
            'place_of_incident' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'affected_infrastructure' => 'nullable|string|max:255',
            'casualties' => 'required|integer',
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

        $request['is_approved'] = Auth::user()->role === 0 ? true : false;
        $request['barangay'] = ucwords(strtolower($request['barangay']));

        Disaster::create($request->all());

        return redirect()->route('disasters.index', ['type' => $request->type])
            ->with('success', 'Disaster case created successfully.');
    }

    // Fetch disaster data dynamically (optional for AJAX requests)
    public function getDisasterData($type)
    {
        $validTypes = ['Flood', 'Earthquake', 'Volcanic Eruption', 'Rebel Encounter'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['error' => 'Invalid disaster type.'], 400);
        }

        $disasters = Disaster::where('type', $type)->get();

        return response()->json($disasters);
    }
}
