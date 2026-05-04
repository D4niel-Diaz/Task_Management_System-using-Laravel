<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the profile settings page.
     */
    public function show()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Update profile details (name, email, password).
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'current_password'      => 'nullable|string',
            'password'              => 'nullable|string|min:6|confirmed',
        ]);

        // If changing password, verify old one first
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
            $user->password = Hash::make($validated['password']);
        }

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload / replace profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => [
                'required',
                'file',
                'image',                    // must be an image
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048',                 // 2 MB
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Delete old photo if it exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo in storage/app/public/profile_photos/{user_id}/
        $path = $request->file('profile_photo')->store("profile_photos/{$user->id}", 'public');

        $user->profile_photo = $path;
        $user->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Delete profile photo.
     */
    public function deletePhoto()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        $user->profile_photo = null;
        $user->save();

        return back()->with('success', 'Profile photo removed.');
    }
}
