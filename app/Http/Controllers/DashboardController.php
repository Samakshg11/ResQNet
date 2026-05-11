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
    public function index(Request $request)
    {
        $user = $request->user();

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
}
