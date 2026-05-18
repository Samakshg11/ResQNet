<?php

namespace App\Http\Controllers;

use App\Models\SOSRequest;
use App\Models\Agency;
use Illuminate\Http\Request;

class SOSController extends Controller
{
    // For admin
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

    // For agency feed
    public function feed(Request $request)
    {
        $query = SOSRequest::with(['user', 'assignedAgency', 'disaster'])
            ->where('status', 'pending')
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $sosRequests = $query->paginate(10);
        return view('sos.feed', compact('sosRequests'));
    }

    // For victim
    public function my(Request $request)
    {
        $user = $request->user();
        
        $activeSos = SOSRequest::where('user_id', $user->id)
            ->whereNotIn('status', ['resolved', 'cancelled'])
            ->with('assignedAgency')
            ->latest()
            ->first();
            
        $history = SOSRequest::where('user_id', $user->id)
            ->whereIn('status', ['resolved', 'cancelled'])
            ->latest()
            ->get();
            
        return view('sos.my', compact('activeSos', 'history'));
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
        
        try {
            broadcast(new \App\Events\NewSOSRequest($sos));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to broadcast NewSOSRequest: ' . $e->getMessage());
        }

        if ($request->user()->role === 'victim') {
            return redirect()->route('sos.my')->with('success', 'SOS request sent successfully! Help is on the way.');
        }
        
        return redirect()->back()->with('success', 'SOS request sent successfully!');
    }
    
    public function cancel(Request $request, string $id)
    {
        $sos = SOSRequest::where('user_id', $request->user()->id)->findOrFail($id);
        
        if (!in_array($sos->status, ['resolved', 'cancelled'])) {
            $sos->update(['status' => 'cancelled']);
            return redirect()->back()->with('success', 'SOS cancelled successfully.');
        }
        
        return redirect()->back()->withErrors(['status' => 'Cannot cancel this SOS request.']);
    }

    public function show(string $id)
    {
        $sos = SOSRequest::with(['user', 'assignedAgency', 'disaster'])->findOrFail($id);
        $agencies = Agency::where('status', 'verified')->get();
        return view('sos.show', compact('sos', 'agencies'));
    }

    // For Gov Admin to assign ANY agency
    public function assign(Request $request, string $id)
    {
        $sos = SOSRequest::findOrFail($id);
        $request->validate(['agency_id' => 'required|exists:agencies,id']);

        if (in_array($sos->status, ['resolved', 'cancelled'], true)) {
            return back()->withErrors(['agency_id' => 'Cannot assign an agency to a closed SOS request.']);
        }

        $agency = Agency::where('status', 'verified')->find($request->agency_id);
        if (! $agency) {
            return back()->withErrors(['agency_id' => 'Only verified agencies can be assigned.']);
        }

        $sos->update([
            'assigned_agency_id' => $agency->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Agency assigned successfully.');
    }
    
    // For Agency Admin to assign themselves
    public function assignToAgency(Request $request, string $id)
    {
        $sos = SOSRequest::findOrFail($id);
        
        if (in_array($sos->status, ['resolved', 'cancelled'], true)) {
            return back()->withErrors(['agency_id' => 'Cannot assign to a closed SOS request.']);
        }

        $agency = $request->user()->agency;
        if (! $agency || $agency->status !== 'verified') {
            return back()->withErrors(['agency_id' => 'You must be a verified agency to claim an SOS.']);
        }
        
        if ($sos->assigned_agency_id && $sos->assigned_agency_id !== $agency->id) {
            return back()->withErrors(['agency_id' => 'This SOS is already assigned to another agency.']);
        }

        $sos->update([
            'assigned_agency_id' => $agency->id,
            'status' => 'assigned',
            'assigned_at' => now(),
        ]);

        return redirect()->back()->with('success', 'SOS assigned to your agency successfully.');
    }

    public function updateStatus(Request $request, string $id)
    {
        $sos = SOSRequest::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,assigned,dispatched,en_route,resolved,cancelled']);
        $nextStatus = $request->status;

        // Security check for agency admin / volunteer
        if (in_array($request->user()->role, ['agency_admin', 'volunteer'])) {
            $userAgencyId = clone $request->user();
            $userAgencyId = $userAgencyId->role === 'agency_admin' ? $userAgencyId->agency->id : $userAgencyId->volunteer->agency_id;
            if ($sos->assigned_agency_id !== $userAgencyId) {
                abort(403);
            }
        }

        if (in_array($nextStatus, ['assigned', 'dispatched', 'en_route'], true) && ! $sos->assigned_agency_id) {
            return back()->withErrors(['status' => 'Assign an agency before moving to operational statuses.']);
        }

        $data = ['status' => $nextStatus];

        if ($nextStatus === 'resolved') {
            $data['resolved_at'] = now();
            if ($sos->assigned_at) {
                $data['response_time_minutes'] = now()->diffInMinutes($sos->assigned_at);
            }
        } elseif ($sos->resolved_at) {
            $data['resolved_at'] = null;
        }

        $sos->update($data);

        return redirect()->back()->with('success', 'Status updated.');
    }
}
