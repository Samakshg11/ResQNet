<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\Disaster;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with(['creator', 'disaster'])->latest()->paginate(15);
        return view('alerts.index', compact('alerts'));
    }

    public function create()
    {
        $disasters = Disaster::where('status', 'active')->get();
        return view('alerts.create', compact('disasters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:emergency,warning,advisory,info',
            'scope' => 'required|in:all,agency_type,region,specific_agencies',
            'disaster_id' => 'nullable|exists:disasters,id',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['delivery_channels'] = ['web', 'email'];

        Alert::create($validated);

        return redirect()->route('alerts.index')->with('success', 'Alert broadcast sent.');
    }
}
