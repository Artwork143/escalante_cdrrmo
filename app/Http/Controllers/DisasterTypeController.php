<?php

namespace App\Http\Controllers;

use App\Models\DisasterType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisasterTypeController extends Controller
{
    /**
     * Display a listing of the disaster types.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $disasterTypes = DisasterType::all();

        if ($request->wantsJson()) {
            return response()->json($disasterTypes);
        }

        return view('disaster_type.index', compact('disasterTypes'));
    }


    /**
     * Show the form for creating a new disaster type.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {
        return view('disaster_type.create');
    }

    /**
     * Store a newly created disaster type in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255|unique:disaster_type,type_name',
        ]);

        DisasterType::create($validatedData);

        return redirect()->route('disaster_type.index');
    }

    /**
     * Show the form for editing the specified disaster type.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id)
    {
        $disasterType = DisasterType::findOrFail($id);

        return view('disaster_type.edit', compact('disasterType'));
    }

    /**
     * Update the specified disaster type in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string|max:255|unique:disaster_type,type_name,' . $id,
        ]);

        // Find the disaster type record
        $disasterType = DisasterType::findOrFail($id);

        // Store the old type_name
        $oldTypeName = $disasterType->type_name;

        // Update the disaster type record
        $disasterType->update($validatedData);

        // Update the 'type' column in the disasters table
        DB::table('disasters')
            ->where('type', $oldTypeName)
            ->update(['type' => $validatedData['type_name']]);

        return redirect()->route('disaster_type.index')->with('success', 'Disaster type updated successfully, and associated disasters updated.');
    }


    /**
     * Remove the specified disaster type from the database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the disaster type record
        $disasterType = DisasterType::findOrFail($id);

        // Check if the disaster type is being used in the disasters table
        $isUsed = DB::table('disasters')->where('type', $disasterType->type_name)->exists();

        if ($isUsed) {
            // Redirect back with an error message
            return redirect()->route('disaster_type.index')->with('error', "The disaster type '{$disasterType->type_name}' is currently in use in the disasters list. Please update the related disasters to a different type before deleting this disaster type.");
        }

        // Delete the disaster type if not in use
        $disasterType->delete();

        return redirect()->route('disaster_type.index')->with('success', 'Disaster type deleted successfully.');
    }
}
