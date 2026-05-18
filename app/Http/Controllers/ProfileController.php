<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function volunteerEdit(Request $request)
    {
        $user = $request->user();
        $volunteer = \App\Models\Volunteer::where('user_id', $user->id)->with('agency')->first();
        return view('profile.volunteer', compact('user', 'volunteer'));
    }

    public function volunteerUpdate(Request $request)
    {
        $user = $request->user();
        $volunteer = \App\Models\Volunteer::where('user_id', $user->id)->first();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'address' => 'nullable|string|max:500',
            'availability' => 'required|in:available,on_mission,unavailable',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
        ]);

        if ($volunteer) {
            $skills = $validated['skills'] ? array_map('trim', explode(',', $validated['skills'])) : [];
            $volunteer->update([
                'skills' => $skills,
                'address' => $validated['address'],
                'availability' => $validated['availability'],
            ]);
        }

        return redirect()->route('volunteer.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
