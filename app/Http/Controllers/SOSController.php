<?php

namespace App\Http\Controllers;

use App\Models\SOSRequest;
use App\Models\Agency;
use Illuminate\Http\Request;

class SOSController extends Controller
{
    public function index(Request $request)
    {
        $query = SOSRequest::with(['user', 'assignedAgency', 'disaster'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $sosRequests = $query->paginate(15);
        return view('sos.index', compact('sosRequests'));
    }

    public function create()
    {
        return view('sos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'severity' => 'required|in:critical,high,medium,low',
            'type' => 'required|in:flood_rescue,medical,evacuation,fire,food,shelter,other',
            'message' => 'nullable|string|max:1000',
            'victim_count' => 'integer|min:1|max:1000',
            'victim_name' => 'nullable|string|max:255',
            'victim_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = 'pending';

        $sos = SOSRequest::create($validated);
        broadcast(new \App\Events\NewSOSRequest($sos));

        return redirect()->route('sos.index')->with('success', 'SOS request sent successfully! Help is on the way.');
    }

    public function show(string $id)
    {
        $sos = SOSRequest::with(['user', 'assignedAgency', 'disaster'])->findOrFail($id);
        $agencies = Agency::where('status', 'verified')->get();
        return view('sos.show', compact('sos', 'agencies'));
    }

    public function assign(Request $request, string $id)
    {
        $sos = SOSRequest::findOrFail($id);
        $request->validate(['agency_id' => 'required|exists:agencies,id']);

        $sos->update([
            'assigned_agency_id' => $request->agency_id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Agency assigned successfully.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $sos = SOSRequest::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,assigned,dispatched,en_route,resolved,cancelled']);

        $data = ['status' => $request->status];

        if ($request->status === 'resolved') {
            $data['resolved_at'] = now();
            if ($sos->assigned_at) {
                $data['response_time_minutes'] = now()->diffInMinutes($sos->assigned_at);
            }
        }

        $sos->update($data);

        return redirect()->back()->with('success', 'Status updated.');
    }
}
