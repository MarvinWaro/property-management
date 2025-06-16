<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:users',
            'role'          => 'required|string|in:admin,cao,staff',
            'department_id' => 'required|exists:departments,id',
            'designation_id'=> 'required|exists:designations,id',
        ]);

        $defaultPassword = '12345678';

        User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'password'             => \Illuminate\Support\Facades\Hash::make($defaultPassword),
            'role'                 => $validated['role'],
            'department_id'        => $validated['department_id'],
            'designation_id'       => $validated['designation_id'],
            'status'               => true,
            'needs_password_change'=> true, // <— Force them to change at next login
        ]);

        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function updateUser(Request $request, $id)
    {
        $validated = $request->validate([
            'role' => 'required|string|in:admin,cao,staff',
            'department_id' => 'required|exists:departments,id',
            'designation_id' => 'required|exists:designations,id',
            'status' => 'required|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->role = $validated['role'];
        $user->department_id = $validated['department_id'];
        $user->designation_id = $validated['designation_id'];
        $user->status = $validated['status'];
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User updated successfully!');
    }

    public function resetPassword($id)
    {
        try {
            $user = User::findOrFail($id);

            // Set the default password
            $defaultPassword = '12345678';
            $user->password = \Illuminate\Support\Facades\Hash::make($defaultPassword);
            $user->needs_password_change = true; // Force password change on next login
            $user->save();

            return redirect()->back()->with('success', "Password reset successfully for {$user->name}! New password: 12345678");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reset password. Please try again.');
        }
    }
}
