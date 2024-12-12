<?php

namespace App\Http\Controllers;

use App\Models\DisasterType;

class DisasterTypeController extends Controller
{
    public function index()
    {
        return response()->json(DisasterType::all());
    }
}
