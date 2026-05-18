@extends('layouts.app')
@section('title', 'Volunteer Profile')
@section('content')

<div class="page-header">
    <div>
        <h1>My Profile</h1>
        <p>Manage your volunteer identity and availability status.</p>
    </div>
</div>

@if(session('success'))
<div class="alert-banner" style="margin-bottom:24px;background:rgba(34,197,94,0.08);border-color:rgba(34,197,94,0.2)">
    <div class="alert-banner-left"><span style="color:var(--green)">✓</span> <span class="alert-banner-text" style="color:var(--green)">{{ session('success') }}</span></div>
</div>
@endif

<div class="grid-2">
    <div class="card">
        <span class="section-title">Personal Information</span>
        <form method="POST" action="{{ route('volunteer.profile.update') }}">
            @csrf @method('PATCH')

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<span style="color:#ef4444;font-size:12px">{{ $message }}</span>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email')<span style="color:#ef4444;font-size:12px">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Specialized Skills <span style="color:var(--text-muted);font-size:11px">(comma separated)</span></label>
                <input type="text" name="skills" class="form-control" value="{{ old('skills', $volunteer ? implode(', ', $volunteer->skills ?? []) : '') }}" placeholder="e.g. First Aid, Search & Rescue, Diver">
            </div>

            <div class="form-group">
                <label class="form-label">Home / Base Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $volunteer->address ?? '') }}">
            </div>

            <div class="form-group">
                <label class="form-label">Availability Status</label>
                <select name="availability" class="form-control" required>
                    <option value="available" {{ ($volunteer->availability ?? '') === 'available' ? 'selected' : '' }}>✅ Available</option>
                    <option value="on_mission" {{ ($volunteer->availability ?? '') === 'on_mission' ? 'selected' : '' }}>🚨 On Mission</option>
                    <option value="unavailable" {{ ($volunteer->availability ?? '') === 'unavailable' ? 'selected' : '' }}>⛔ Unavailable</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </form>
    </div>

    <div>
        {{-- Agency Card --}}
        <div class="card" style="margin-bottom:20px">
            <span class="section-title">Agency Assignment</span>
            @if($volunteer && $volunteer->agency)
                <div style="display:flex;align-items:center;gap:16px;margin-top:12px">
                    <div style="width:48px;height:48px;border-radius:12px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:var(--accent)">{{ substr($volunteer->agency->name, 0, 1) }}</div>
                    <div>
                        <div style="font-weight:600;color:var(--text)">{{ $volunteer->agency->name }}</div>
                        <div style="font-size:12px;color:var(--text-secondary)">{{ $volunteer->agency->getTypeLabel() }} · {{ $volunteer->agency->region }}</div>
                    </div>
                </div>
            @else
                <p style="color:var(--text-muted);font-size:13px;margin-top:12px">You are not assigned to any agency yet.</p>
            @endif
        </div>

        {{-- Quick Stats --}}
        <div class="card" style="margin-bottom:20px">
            <span class="section-title">Quick Stats</span>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px">
                <div style="text-align:center;padding:16px;background:var(--surface);border-radius:12px;border:1px solid var(--border)">
                    <div style="font-size:24px;font-weight:700;color:var(--accent)">{{ $volunteer ? \App\Models\SOSRequest::where('agency_id', $volunteer->agency_id)->where('status', 'resolved')->count() : 0 }}</div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px">Missions Completed</div>
                </div>
                <div style="text-align:center;padding:16px;background:var(--surface);border-radius:12px;border:1px solid var(--border)">
                    <div style="font-size:24px;font-weight:700;color:var(--green)">
                        @if($volunteer)
                            {{ ucfirst(str_replace('_', ' ', $volunteer->availability)) }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div style="font-size:11px;color:var(--text-muted);margin-top:4px">Current Status</div>
                </div>
            </div>
        </div>

        {{-- Account Info --}}
        <div class="card">
            <span class="section-title">Account Details</span>
            <div style="display:grid;gap:12px;margin-top:12px">
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="font-size:13px;color:var(--text-muted)">Account ID</span>
                    <span style="font-size:13px;font-family:monospace;color:var(--text)">RQN-{{ str_pad($user->id, 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border)">
                    <span style="font-size:13px;color:var(--text-muted)">Role</span>
                    <span class="badge badge-available">VOLUNTEER</span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:8px 0">
                    <span style="font-size:13px;color:var(--text-muted)">Joined</span>
                    <span style="font-size:13px;color:var(--text)">{{ $user->created_at->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
