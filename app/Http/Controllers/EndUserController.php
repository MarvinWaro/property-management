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
        $endUsers = EndUser::where('excluded', 0) // Exclude users marked as deleted
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone_number', 'like', "%{$search}%");
            })
            ->paginate(5); // 5 items per page

        return view('manage-users.index', compact('endUsers'));
    }

    public function create()
    {
        return view('manage-users.create');
    }

    public function store(Request $request)
    {
        // Validate input, excluding unique checks for now
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|digits_between:1,15',
            'department' => 'required|string|in:Admin Department,Technical Department,UNIFAST',
        ]);

        // Check if an ACTIVE user exists with the same email
        $existingEmailUser = EndUser::where('excluded', 0)
            ->where('email', $request->email)
            ->first();

        // Check if an ACTIVE user exists with the same phone number
        $existingPhoneUser = EndUser::where('excluded', 0)
            ->where('phone_number', $request->phone_number)
            ->first();

        // If either already exists, show the correct error message
        if ($existingEmailUser || $existingPhoneUser) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'email' => $existingEmailUser ? 'Email is already taken.' : null,
                    'phone_number' => $existingPhoneUser ? 'Phone number is already taken.' : null,
                ]);
        }

        // Check if an EXCLUDED user exists with the same email or phone number
        $excludedUser = EndUser::where('excluded', 1)
            ->where(function ($query) use ($request) {
                $query->where('email', $request->email)
                        ->orWhere('phone_number', $request->phone_number);
            })
            ->first();

        if ($excludedUser) {
            // Reactivate the excluded user
            $excludedUser->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'department' => $request->department,
                'excluded' => 0, // Mark as active again
                'active' => 1
            ]);

            return redirect()->route('end_users.index')->with('success', 'User reactivated successfully.');
        }

        // If no excluded user is found, create a new user
        EndUser::create($request->all());

        return redirect()->route('end_users.index')->with('success', 'User created successfully.');
    }


    // Show Edit Form
    public function edit(EndUser $endUser)
    {
        return view('manage-users.edit', compact('endUser'));
    }

    // Handle Update Request
    public function update(Request $request, EndUser $endUser)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:end_users,email,' . $endUser->id,
            'phone_number' => 'required|digits_between:1,15|unique:end_users,phone_number,' . $endUser->id,
            'department' => 'required|string|in:Admin Department,Technical Department,UNIFAST',
        ]);

        // Update user
        $endUser->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'department' => $request->department,
        ]);

        // Redirect with success message
        return redirect()->route('end_users.index')->with('success', 'User updated successfully');
    }

    public function destroy(EndUser $endUser)
    {
        // Check if the user is already excluded
        if ($endUser->excluded) {
            return redirect()->route('end_users.index')->with('error', 'User is already excluded.');
        }

        // Mark the user as excluded and inactive
        $endUser->update([
            'excluded' => 1, // Mark user as excluded
            'active' => 0    // Mark user as inactive
        ]);

        return redirect()->route('end_users.index')->with('success', 'User has been marked as excluded.');
    }


}
