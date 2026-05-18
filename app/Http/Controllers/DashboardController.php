<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Alert;
use App\Models\Disaster;
use App\Models\Resource;
use App\Models\SOSRequest;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // For Gov Admin
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->role === 'agency_admin') {
            return redirect()->route('agency.dashboard');
        } elseif ($user->role === 'volunteer') {
            return redirect()->route('volunteer.dashboard');
        } elseif ($user->role === 'victim') {
            return redirect()->route('sos.my');
        }

        $stats = [
            'active_disasters' => Disaster::where('status', 'active')->count(),
            'total_sos' => SOSRequest::count(),
            'pending_sos' => SOSRequest::where('status', 'pending')->count(),
            'resolved_sos' => SOSRequest::where('status', 'resolved')->count(),
            'total_agencies' => Agency::where('status', 'verified')->count(),
            'deployed_agencies' => Agency::where('is_deployed', true)->count(),
            'total_volunteers' => Volunteer::count(),
            'available_volunteers' => Volunteer::where('availability', 'available')->count(),
            'total_resources' => Resource::count(),
            'resource_shortages' => Resource::whereColumn('available_quantity', '<=', 'minimum_threshold')->count(),
        ];

        $recentSOS = SOSRequest::with(['user', 'assignedAgency'])
            ->latest()
            ->limit(10)
            ->get();

        $activeDisasters = Disaster::where('status', 'active')
            ->latest('started_at')
            ->get();

        $recentAlerts = Alert::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact('stats', 'recentSOS', 'activeDisasters', 'recentAlerts', 'user'));
    }
    
    // For Volunteer
    public function volunteer(Request $request)
    {
        $user = $request->user();
        $volunteer = $user->volunteer;
        
        $activeMissions = collect();
        if ($volunteer && $volunteer->agency_id) {
            $activeMissions = SOSRequest::where('assigned_agency_id', $volunteer->agency_id)
                ->whereNotIn('status', ['resolved', 'cancelled'])
                ->with('disaster')
                ->latest()
                ->get();
        }
        
        $stats = [
            'total_missions' => 12,
            'hours_logged' => 45,
        ];
        
        return view('dashboard.volunteer', compact('user', 'volunteer', 'activeMissions', 'stats'));
    }
    
    // For Agency Admin
    public function agency(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        
        $stats = [
            'active_missions' => SOSRequest::where('assigned_agency_id', $agency->id)
                ->whereNotIn('status', ['resolved', 'cancelled'])->count(),
            'resolved_missions' => SOSRequest::where('assigned_agency_id', $agency->id)
                ->where('status', 'resolved')->count(),
            'total_volunteers' => Volunteer::where('agency_id', $agency->id)->count(),
        ];
        
        $activeSos = SOSRequest::where('assigned_agency_id', $agency->id)
            ->whereNotIn('status', ['resolved', 'cancelled'])
            ->latest()
            ->get();
            
        return view('dashboard.agency', compact('user', 'agency', 'stats', 'activeSos'));
    }
}
