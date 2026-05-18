<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Agency;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials['email'] = strtolower(trim($credentials['email']));

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            $user->update(['last_seen_at' => now()]);

            switch ($user->role) {
                case 'victim':
                    return redirect()->route('sos.my');
                case 'volunteer':
                    return redirect()->route('volunteer.dashboard');
                case 'agency_admin':
                    return redirect()->route('agency.dashboard');
                case 'gov_admin':
                case 'super_admin':
                default:
                    return redirect()->route('dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $intent = $request->input('intent', 'victim');
        
        if ($intent === 'agency') {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
                'agency_name' => 'required|string|max:255',
                'registration_number' => 'required|string|max:255|unique:agencies',
                'agency_type' => 'required|in:medical,fire_rescue,flood_rescue,food_supply,police,ngo,ambulance,civil_defense',
                'contact_phone' => 'required|string|max:20',
                'address' => 'required|string',
                'region' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'total_teams' => 'required|integer|min:1',
            ]);
            $validated['email'] = strtolower(trim($validated['email']));

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'agency_admin',
                'phone' => $validated['contact_phone'],
            ]);
            
            Agency::create([
                'user_id' => $user->id,
                'name' => $validated['agency_name'],
                'registration_number' => $validated['registration_number'],
                'type' => $validated['agency_type'],
                'contact_email' => $validated['email'],
                'contact_phone' => $validated['contact_phone'],
                'address' => $validated['address'],
                'region' => $validated['region'],
                'state' => $validated['state'],
                'total_teams' => $validated['total_teams'],
                'status' => 'pending',
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/agency/pending')->with('success', 'Application submitted. NDMA will verify your agency within 24 hours.');
        } else {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8|confirmed',
                'phone' => 'nullable|string|max:20',
            ]);
            $validated['email'] = strtolower(trim($validated['email']));

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'victim',
                'phone' => $validated['phone'] ?? null,
            ]);

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/sos/my')->with('success', 'Registered successfully.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
