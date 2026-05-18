<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::with(['user', 'agency'])->latest();

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        $volunteers = $query->paginate(15);
        return view('volunteers.index', compact('volunteers'));
    }

    public function show(string $id)
    {
        $volunteer = Volunteer::with(['user', 'agency'])->findOrFail($id);
        return view('volunteers.show', compact('volunteer'));
    }

    public function my(Request $request)
    {
        $agency = $request->user()->agency;
        if (!$agency) {
            return redirect()->route('agency.dashboard')->withErrors('You do not have an agency profile.');
        }

        $volunteers = Volunteer::with('user')
            ->where('agency_id', $agency->id)
            ->latest()
            ->paginate(15);

        return view('volunteers.my', compact('volunteers', 'agency'));
    }

    public function storeMy(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'skills' => 'nullable|string',
            'address' => 'nullable|string|max:500'
        ]);

        $agency = $request->user()->agency;

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt('password123'),
            'role' => 'volunteer',
        ]);

        $skills = $request->skills ? array_map('trim', explode(',', $request->skills)) : [];
        
        Volunteer::create([
            'user_id' => $user->id,
            'agency_id' => $agency->id,
            'address' => $request->address,
            'skills' => $skills,
            'availability' => 'available',
        ]);

        return redirect()->back()->with('success', 'Volunteer added successfully. Default password is password123.');
    }
}
