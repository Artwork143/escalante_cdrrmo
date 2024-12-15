<?php

namespace App\Http\Controllers;

use App\Models\RescueTeam;
use Illuminate\Http\Request;

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
        $RescueTeam = RescueTeam::findOrFail($id);
        $RescueTeam->delete();

        return redirect()->route('rescue_team.index');
    }
}
