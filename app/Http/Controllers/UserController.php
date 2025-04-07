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
            'password'      => 'required|string|min:8|confirmed',
            'role'          => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'designation_id'=> 'required|exists:designations,id',
            'status'        => 'required|boolean',
        ]);

        \App\Models\User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password'      => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'role'          => $validated['role'],
            'department_id' => $validated['department_id'],
            'designation_id'=> $validated['designation_id'],
            'status'        => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'User created successfully!');
    }


    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->department_id = $request->input('department_id');
        $user->designation_id = $request->input('designation_id');
        $user->status = $request->input('status');
        $user->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'User updated successfully!');
    }






}
