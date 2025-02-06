<?php

namespace App\Http\Controllers;

use App\Models\EndUser;
use Illuminate\Http\Request;

class EndUserController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->get('search');

        // Paginate the users with 5 users per page, applying the search filter
        $endUsers = EndUser::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
        })->paginate(5); // 5 items per page

        return view('manage-users.index', compact('endUsers'));
    }

    public function create()
    {
        return view('manage-users.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:end_users,email',
            'phone_number' => 'required|digits_between:1,15|unique:end_users,phone_number',
            'department' => 'required|string', // Make sure department is required
        ]);

        // Create the new user including department
        EndUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'department' => $request->department, // Ensure department is included
        ]);

        return redirect()->route('end_users.index')->with('success', 'User created successfully!');
    }



}
