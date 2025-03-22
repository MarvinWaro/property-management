<?php

namespace App\Http\Controllers;

use App\Models\EndUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EndUserController extends Controller
{
    public function index(Request $request)
    {
        // Get the search term from the request
        $search = $request->get('search');

        // Paginate all users (active or excluded), applying the search filter.
        $endUsers = EndUser::when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%");
                })
                ->orderBy('created_at', 'desc') // Newest first
                ->paginate(5); // 5 items per page

        return view('manage-users.index', compact('endUsers'));
    }

    public function create()
    {
        return view('manage-users.create');
    }

    public function store(Request $request)
    {
        // Validate input including the picture file
        // (Removed phone_number from validation)
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email',
            'department' => 'required|string|in:Admin Department,Technical Department,UNIFAST',
            'picture'    => 'nullable|image|max:10240', // 10MB max
        ]);

        // Check if email is already taken by an active user
        $emailExists = EndUser::where('excluded', 0)
            ->where('email', $request->email)
            ->exists();

        // If email is taken by an active user, show an error.
        if ($emailExists) {
            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'email' => 'Email is already taken.',
                ]);
        }

        // Handle file upload if exists
        $picturePath = null;
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('pictures', 'public');
        }

        // ----------------------------------------------------------------
        // 1) Attempt to find an *excluded* user by matching email (case-insensitive)
        // ----------------------------------------------------------------
        $excludedUser = EndUser::where('excluded', 1)
            ->whereRaw('LOWER(email) = ?', [strtolower($request->email)])
            ->first();

        // If found, reactivate it with new info
        if ($excludedUser) {
            $excludedUser->update([
                'name'     => $request->name,
                'email'    => $request->email,
                'department' => $request->department,
                'picture'  => $picturePath,
                'excluded' => 0, // Mark as active
                'active'   => 1,
            ]);

            return redirect()->route('end_users.index')
                ->with('success', 'User reactivated successfully.');
        }

        // ----------------------------------------------------------------
        // 2) Otherwise, create a brand-new user
        // ----------------------------------------------------------------
        EndUser::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'department' => $request->department,
            'picture'    => $picturePath,
            'active'     => 1,
            'excluded'   => 0,
        ]);

        return redirect()->route('end_users.index')->with('success', 'User added successfully.');
    }

    // Show Edit Form
    public function edit(EndUser $endUser)
    {
        return view('manage-users.edit', compact('endUser'));
    }

    // Handle Update Request
    public function update(Request $request, EndUser $endUser)
    {
        // Validate input including the picture file
        // (Removed phone_number from validation)
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:end_users,email,' . $endUser->id,
            'department' => 'required|string|in:Admin Department,Technical Department,UNIFAST',
            'picture'    => 'nullable|image|max:10240', // 10MB max
        ]);

        // Start with the current picture path
        $picturePath = $endUser->picture;

        // Convert the remove_photo flag to boolean
        if ($request->boolean('remove_photo')) {
            // Delete the old picture if it exists
            if ($endUser->picture) {
                Storage::disk('public')->delete($endUser->picture);
            }
            // Clear the picture path so that the DB will be set to null
            $picturePath = null;
        }

        // Handle file upload if a new picture is provided
        if ($request->hasFile('picture')) {
            $picturePath = $request->file('picture')->store('pictures', 'public');
        }

        // Update the user record (Removed phone_number field)
        $endUser->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'department' => $request->department,
            'picture'    => $picturePath,
        ]);

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
            'active'   => 0  // Mark user as inactive
        ]);

        return redirect()->route('end_users.index')->with('success', 'User has been removed.');
    }
}
