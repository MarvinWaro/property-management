<?php

namespace App\Http\Controllers;

use App\Models\EndUser;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    public function index()
    {
        $endUsers = EndUser::paginate(5); // 10 users per page
        return view('manage-users.index', compact('endUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:end_users,email',
            'phone_number' => 'required|string|max:15',
        ]);

        // Create the new user
        EndUser::create($request->all());

        // Redirect back to the users index or dashboard
        return redirect()->route('end_users.index')->with('success', 'User created successfully');
    }



}
