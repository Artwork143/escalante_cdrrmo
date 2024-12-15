<?php

namespace App\Http\Controllers;

use App\Models\DisasterType;
use Illuminate\Http\Request;
use App\Models\MedicalCase;
use App\Models\RescueTeam;
use App\Models\VehicularAccident;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DssController extends Controller
{
    /**
     * Display a listing of the medical cases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dss_tools.index');
    }

    public function index2()
    {
        return view('dss_tools.vehicular');
    }

    public function index3()
    {
         // Fetch all disaster types
         $disasterTypes = DisasterType::all();
        return view('dss_tools.disaster', compact('disasterTypes'));
    }
}