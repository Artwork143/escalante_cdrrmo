<?php

namespace App\Http\Controllers;

use App\Models\RescueTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RescueTeamController extends Controller
{
    /**
     * Display a listing of the rescue team types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $rescueTeams = RescueTeam::all(); // Fetch all rescue teams
        return view('rescue_team.index', compact('rescueTeams'));
    }



    /**
     * Show the form for creating a new rescue team type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return view('rescue_team.create');
    }

    /**
     * Store a newly created rescue team type in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255|unique:rescue_teams,team_name',
        ]);

        $RescueTeam = RescueTeam::create($validatedData);

        return redirect()->route('rescue_team.index', compact('RescueTeam'));
    }

    /**
     * Show the form for editing the specified rescue team type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $rescueTeam = RescueTeam::findOrFail($id);

        return view('rescue_team.edit', compact('rescueTeam'));
    }

    /**
     * Update the specified rescue team type in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'team_name' => 'required|string|max:255|unique:rescue_teams,team_name,' . $id,
        ]);

        $RescueTeam = RescueTeam::findOrFail($id);
        $RescueTeam->update($validatedData);

        return redirect()->route('rescue_team.index');
    }

    /**
     * Remove the specified rescue team type from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the rescue team record
        $rescueTeam = RescueTeam::findOrFail($id);

        // Check if the rescue team is being used in related tables
        $isUsedInDisasters = DB::table('disasters')->where('rescue_team', $rescueTeam->team_name)->exists();
        $isUsedInMedicalCases = DB::table('medical_cases')->where('rescue_team', $rescueTeam->team_name)->exists();
        $isUsedInVehicularAccidents = DB::table('vehicular_accidents')->where('rescue_team', $rescueTeam->team_name)->exists();

        if ($isUsedInDisasters || $isUsedInMedicalCases || $isUsedInVehicularAccidents) {
            // Redirect back with an error message
            return redirect()->route('rescue_team.index')->with('error', "The rescue team '{$rescueTeam->team_name}' is currently in use in related records (disasters, medical cases, or vehicular accidents). Please reassign or remove references to this rescue team before deletion.");
        }

        // Delete the rescue team if not in use
        $rescueTeam->delete();

        return redirect()->route('rescue_team.index')->with('success', 'Rescue team deleted successfully.');
    }
}
