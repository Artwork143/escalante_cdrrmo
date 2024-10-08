<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Fetch all users
        $users = User::all();

        // Return the view with the list of users
        return view('admin', compact('users'));
    }
}
