<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'signature' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024', // 1MB max
        ]);

        $user = Auth::user();

        // Delete old signature if exists
        if ($user->signature_path && Storage::disk('public')->exists($user->signature_path)) {
            Storage::disk('public')->delete($user->signature_path);
        }

        // Store the new signature
        $path = $request->file('signature')->store('signatures', 'public');

        // Update user record
        $user->signature_path = $path;
        $user->save();

        return redirect()->back()->with('signature_success', 'Signature uploaded successfully');
    }

    public function delete(Request $request)
    {
        $user = Auth::user();

        if ($user->signature_path && Storage::disk('public')->exists($user->signature_path)) {
            Storage::disk('public')->delete($user->signature_path);
        }

        $user->signature_path = null;
        $user->save();

        return redirect()->back()->with('signature_success', 'Signature removed successfully');
    }
}
