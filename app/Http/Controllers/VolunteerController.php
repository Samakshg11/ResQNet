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
}
