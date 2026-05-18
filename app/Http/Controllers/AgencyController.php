<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $query = Agency::with(['user', 'sosRequests'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('region')) {
            $query->where('region', 'like', '%' . $request->region . '%');
        }

        $agencies = $query->paginate(15);
        return view('agencies.index', compact('agencies'));
    }

    public function show(string $id)
    {
        $agency = Agency::with(['user', 'resources', 'volunteers.user', 'reports'])->findOrFail($id);
        return view('agencies.show', compact('agency'));
    }

    public function my(Request $request)
    {
        $agency = clone $request->user()->agency;
        if (!$agency) {
            return redirect()->route('dashboard')->withErrors('No agency profile found.');
        }

        $agency->load(['user', 'resources', 'volunteers.user', 'reports']);
        return view('agencies.show', compact('agency'));
    }

    public function create()
    {
        return view('agencies.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'registration_number' => 'required|string|unique:agencies',
            'type' => 'required|in:medical,fire_rescue,flood_rescue,food_supply,police,ngo,ambulance,civil_defense',
            'description' => 'nullable|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'address' => 'required|string',
            'region' => 'required|string',
            'state' => 'required|string',
            'total_teams' => 'nullable|integer|min:0',
        ]);

        $validated['registration_number'] = strtoupper(trim($validated['registration_number']));
        $validated['contact_email'] = strtolower(trim($validated['contact_email']));
        $validated['contact_phone'] = trim($validated['contact_phone']);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';
        $validated['country'] = 'IND';

        Agency::create($validated);

        return redirect()->route('agencies.index')->with('success', 'Agency registered. Pending verification.');
    }

    public function verify(Request $request, string $id)
    {
        $agency = Agency::findOrFail($id);
        $agency->update([
            'status' => 'verified',
            'verified_by' => $request->user()->id,
            'verified_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Agency verified successfully.');
    }

    public function suspend(string $id)
    {
        $agency = Agency::findOrFail($id);
        $agency->update(['status' => 'suspended']);
        return redirect()->back()->with('success', 'Agency suspended.');
    }

    public function reject(string $id)
    {
        $agency = Agency::findOrFail($id);
        $agency->update(['status' => 'rejected']);
        return redirect()->back()->with('success', 'Agency registration rejected.');
    }
}
