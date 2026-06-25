<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ResourceController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'category' => ['nullable', Rule::in(array_keys(Resource::CATEGORIES))],
            'status' => ['nullable', Rule::in(array_keys(Resource::STATUSES))],
        ]);

        $query = Resource::with('agency')->latest();

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $resources = $query->paginate(15);
        $shortages = Resource::whereColumn('available_quantity', '<=', 'minimum_threshold')->with('agency')->get();

        return view('resources.index', compact('resources', 'shortages'));
    }

    public function agencyIndex(Request $request)
    {
        $agency = clone $request->user()->agency;
        if (!$agency) {
            return redirect()->route('agency.dashboard')->withErrors('You do not have an agency profile.');
        }

        $filters = $request->validate([
            'category' => ['nullable', Rule::in(array_keys(Resource::CATEGORIES))],
            'status' => ['nullable', Rule::in(array_keys(Resource::STATUSES))],
        ]);

        $query = Resource::where('agency_id', $agency->id)->latest();

        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $resources = $query->paginate(15);
        $shortages = Resource::where('agency_id', $agency->id)
            ->whereColumn('available_quantity', '<=', 'minimum_threshold')
            ->get();

        return view('agencies.resources', compact('resources', 'shortages', 'agency'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'name' => 'required|string|max:255',
            'category' => ['required', Rule::in(array_keys(Resource::CATEGORIES))],
            'total_quantity' => 'required|integer|min:1',
            'available_quantity' => 'required|integer|min:0',
            'unit' => 'required|string',
            'minimum_threshold' => 'nullable|integer|min:0',
        ]);

        if ($validated['available_quantity'] > $validated['total_quantity']) {
            return back()
                ->withErrors(['available_quantity' => 'Available quantity cannot exceed total quantity.'])
                ->withInput();
        }

        $validated['minimum_threshold'] = $validated['minimum_threshold'] ?? 0;

        $validated['status'] = 'available';

        Resource::create($validated);

        if (auth()->user()->role === 'agency_admin') {
            return redirect()->route('agency.resources.index')->with('success', 'Resource added.');
        }

        return redirect()->route('resources.index')->with('success', 'Resource added.');
    }

    public function deploy(Request $request, string $id)
    {
        $resource = Resource::findOrFail($id);

        if ($resource->status === 'depleted' || $resource->available_quantity === 0) {
            return back()->withErrors(['quantity' => 'This resource is depleted and cannot be deployed.']);
        }

        $qty = $request->validate(['quantity' => 'required|integer|min:1'])['quantity'];

        if ($qty > $resource->available_quantity) {
            return back()->withErrors(['quantity' => 'Insufficient quantity available.']);
        }

        $remaining = $resource->available_quantity - $qty;
        $resource->update([
            'available_quantity' => $remaining,
            'deployed_quantity' => $resource->deployed_quantity + $qty,
            'status' => $remaining === 0 ? 'depleted' : 'available',
        ]);

        return redirect()->back()->with('success', 'Resource deployed.');
    }
}
