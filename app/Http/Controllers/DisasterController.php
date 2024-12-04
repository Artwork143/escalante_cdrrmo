<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disaster;

class DisasterController extends Controller
{
    // Index method to load hardcoded disaster types for dropdown
    public function index()
    {
        // Hardcoded disaster types
        $disasterTypes = ['Flood', 'Earthquake', 'Volcanic Eruption', 'Rebel Encounter'];

        return view('disasters.index', compact('disasterTypes'));
    }

    // Get disaster data based on the selected disaster type
    public function getDisasterData($type)
    {
        // Validate if the disaster type is valid
        $validTypes = ['Flood', 'Earthquake', 'Volcanic Eruption', 'Rebel Encounter'];
        if (!in_array($type, $validTypes)) {
            return response()->json(['error' => 'Invalid disaster type.'], 400);
        }

        // Query data based on disaster type
        $disasterData = Disaster::where('type', $type)->get();

        // Prepare dynamic headers based on disaster type
        $headers = $this->getDynamicHeaders($type);

        // Map the disaster data to rows
        $rows = $disasterData->map(function ($disaster) use ($type) {
            return $this->mapDisasterData($disaster, $type);
        });

        // Return formatted response
        return response()->json([
            'headers' => $headers,
            'rows' => $rows,
        ]);
    }

    // Get dynamic headers based on the disaster type
    private function getDynamicHeaders($type)
    {
        $baseHeaders = [
            'Date',
            'Rescue Team',
            'Place of Incident',
            'City',
            'Barangay',
            'Type',
            'Affected Infrastructure',
            'Casualties',
        ];

        switch ($type) {
            case 'Flood':
                return array_merge($baseHeaders, [
                    'Current Water Level',
                    'Water Level Trend',
                ]);
            case 'Earthquake':
                return array_merge($baseHeaders, [
                    'Intensity Level',
                    'Aftershocks',
                ]);
            case 'Volcanic Eruption':
                return array_merge($baseHeaders, [
                    'Eruption Type',
                    'Eruption Intensity',
                ]);
            case 'Rebel Encounter':
                return array_merge($baseHeaders, [
                    'Involved Parties',
                    'Triggering Event',
                    'Nature of Encounter',
                    'Duration',
                ]);
            default:
                return $baseHeaders;
        }
    }

    // Map the disaster data dynamically based on the disaster type
    private function mapDisasterData($disaster, $type)
    {
        $data = [
            $disaster->date,
            $disaster->rescue_team,
            $disaster->place_of_incident,
            $disaster->city,
            $disaster->barangay,
            $disaster->type,
            $disaster->affected_infrastructure,
            $disaster->casualties,
        ];

        switch ($type) {
            case 'Flood':
                return array_merge($data, [
                    $disaster->current_water_level,
                    $disaster->water_level_trend,
                ]);
            case 'Earthquake':
                return array_merge($data, [
                    $disaster->intensity_level,
                    $disaster->aftershocks,
                ]);
            case 'Volcanic Eruption':
                return array_merge($data, [
                    $disaster->eruption_type,
                    $disaster->eruption_intensity,
                ]);
            case 'Rebel Encounter':
                return array_merge($data, [
                    $disaster->involved_parties,
                    $disaster->triggering_event,
                    $disaster->nature_of_encounter,
                    $disaster->duration,
                ]);
            default:
                return $data;
        }
    }

    // Show form to create a new disaster case
    public function create()
    {
        return view('disasters.create');
    }

    // Store a new disaster case in the database
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'date' => 'required|date',
            'rescue_team' => 'required|string|max:255',
            'place_of_incident' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
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

        // Store the new disaster record
        Disaster::create($request->all());

        // Redirect with success message
        return redirect()->route('disasters.index')->with('success', 'Disaster case created successfully.');
    }
}
