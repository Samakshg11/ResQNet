<?php

namespace App\Http\Controllers;

use App\Models\Disaster;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DisasterController extends Controller
{
    public function index()
    {
        $disasters = Disaster::latest('started_at')->get();
        $sosRequests = \App\Models\SOSRequest::with('user')->latest()->take(20)->get();
        return view('disasters.index', compact('disasters', 'sosRequests'));
    }

    public function show(string $id)
    {
        $disaster = Disaster::findOrFail($id);
        $sosRequests = $disaster->sosRequests()->with(['user', 'assignedAgency'])->latest()->get();
        return view('disasters.show', compact('disaster', 'sosRequests'));
    }

    public function create()
    {
        return view('disasters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:flood,earthquake,cyclone,fire,landslide,tsunami,drought,industrial,other',
            'severity' => 'required|in:low,medium,high,critical',
            'epicenter_lat' => 'nullable|numeric',
            'epicenter_lng' => 'nullable|numeric',
            'radius_km' => 'nullable|numeric',
            'estimated_affected' => 'nullable|integer|min:0',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['started_at'] = now();
        $validated['status'] = 'active';

        Disaster::create($validated);

        return redirect()->route('disasters.index')->with('success', 'Disaster registered successfully.');
    }

    public function update(Request $request, string $id)
    {
        $disaster = Disaster::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'in:monitoring,active,contained,resolved',
            'severity' => 'in:low,medium,high,critical',
            'rescued_count' => 'nullable|integer|min:0',
            'confirmed_casualties' => 'nullable|integer|min:0',
        ]);

        if (isset($validated['status']) && $validated['status'] === 'contained') {
            $validated['contained_at'] = now();
        }

        $disaster->update($validated);

        return redirect()->back()->with('success', 'Disaster updated.');
    }
}
