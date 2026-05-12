<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Disaster;
use App\Models\SOSRequest;
use App\Models\Resource;
use App\Models\Volunteer;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index()
    {
        $rescueRate = SOSRequest::where('status', 'resolved')->count();
        $totalSOS = SOSRequest::count();
        $avgResponseTime = SOSRequest::whereNotNull('response_time_minutes')->avg('response_time_minutes');

        $sosBySeverity = SOSRequest::selectRaw('severity, count(*) as count')
            ->groupBy('severity')
            ->pluck('count', 'severity');

        $sosByType = SOSRequest::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $sosByStatus = SOSRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $disastersByType = Disaster::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $agenciesByType = Agency::where('status', 'verified')
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        $resourcesByCategory = Resource::selectRaw('category, sum(available_quantity) as total')
            ->groupBy('category')
            ->pluck('total', 'category');

        $monthlySOSTrend = SOSRequest::selectRaw("strftime('%Y-%m', created_at) as month, count(*) as count")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $sosMarkers = SOSRequest::select('latitude', 'longitude', 'severity', 'type', 'status')->get();

        return view('analytics.index', compact(
            'rescueRate', 'totalSOS', 'avgResponseTime',
            'sosBySeverity', 'sosByType', 'sosByStatus',
            'disastersByType', 'agenciesByType', 'resourcesByCategory',
            'monthlySOSTrend', 'sosMarkers'
        ));
    }
}
