@extends('layouts.app')
@section('title', $volunteer->user->name)
@section('content')
<div class="page-header">
    <div>
        <h1>Volunteer Portal</h1>
        <p>Manage your availability, assignments, and skills.</p>
    </div>
    <div>
        <span class="badge badge-{{ $volunteer->availability === 'available' ? 'available' : 'pending' }}" style="font-size:11px;padding:6px 14px">
            <span style="display:inline-block;width:6px;height:6px;border-radius:50%;background:{{ $volunteer->availability === 'available' ? 'var(--green)' : 'var(--yellow)' }};margin-right:6px"></span>
            {{ $volunteer->availability === 'available' ? 'Available for Deployment' : ucfirst(str_replace('_',' ',$volunteer->availability)) }}
        </span>
    </div>
</div>

<div class="grid-2" style="margin-bottom:28px">
    {{-- Profile Card --}}
    <div class="card">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
            <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--accent),#c45040);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:#0a0a0a">{{ substr($volunteer->user->name,0,1) }}</div>
            <div>
                <h2 style="font-size:20px;font-weight:700;color:var(--text)">{{ $volunteer->user->name }}</h2>
                <p style="font-size:13px;color:var(--text-secondary)">Senior Field Responder</p>
                <div style="display:flex;align-items:center;gap:4px;margin-top:4px">
                    <span style="color:var(--yellow);font-size:13px">★</span>
                    <span style="font-size:13px;color:var(--text-secondary)">{{ number_format($volunteer->rating,1) }} ({{ $volunteer->total_missions }} Missions)</span>
                </div>
            </div>
        </div>

        <div class="section-title-sm" style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted);margin-bottom:12px">Verified Skills</div>
        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:24px">
            @foreach(($volunteer->skills ?? ['First Aid', 'Logistics']) as $skill)
            <span style="display:inline-flex;align-items:center;gap:6px;padding:8px 14px;background:var(--bg);border:1px solid var(--border);border-radius:8px;font-size:12px;font-weight:500;color:var(--text-secondary)">
                <i class="fas fa-check-square" style="color:var(--accent);font-size:11px"></i> {{ $skill }}
            </span>
            @endforeach
        </div>

        <div style="display:flex;gap:20px">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Active Hours</div>
                <div style="font-size:28px;font-weight:300;color:var(--text)">{{ $volunteer->total_missions * 7 }} <span style="font-size:14px;color:var(--text-secondary)">hrs</span></div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Clearance Level</div>
                <div style="font-size:28px;font-weight:300;color:var(--text)">Alpha <span style="font-size:14px;color:var(--text-secondary)">Tier 2</span></div>
            </div>
        </div>
    </div>

    {{-- Active Deployment --}}
    <div class="card" style="background:var(--bg);border-color:var(--border)">
        <div style="margin-bottom:20px">
            <span class="badge badge-active" style="margin-right:8px;font-size:10px">⚠ Active Deployment</span>
            <span class="badge badge-high" style="font-size:10px">Priority: High</span>
        </div>
        <h2 style="font-family:var(--serif);font-size:26px;font-weight:400;color:var(--text);margin-bottom:12px">Flood Evacuation Coordination</h2>
        <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;margin-bottom:28px">Sector 4, Riverside District. Assist with immediate perimeter securing and medical triage for incoming evacuees.</p>
        <div style="display:flex;gap:12px">
            <a href="#" class="btn btn-outline" style="border-color:var(--accent);color:var(--accent)"><i class="fas fa-route"></i> Open Map Route</a>
            <a href="#" class="btn btn-outline"><i class="fas fa-phone"></i> Contact Unit Lead</a>
        </div>
    </div>
</div>

{{-- Upcoming Assignments --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
        <span class="section-title" style="margin-bottom:0">Upcoming Assignments</span>
        <a href="#" style="font-size:13px;color:var(--text-secondary);font-weight:500">View Schedule →</a>
    </div>
    <div style="padding:20px;border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;gap:16px;margin-bottom:12px">
        <div style="width:44px;height:44px;border-radius:10px;background:var(--blue-soft);display:flex;align-items:center;justify-content:center;color:var(--blue);font-size:18px"><i class="fas fa-box"></i></div>
        <div style="flex:1">
            <div style="font-size:15px;font-weight:600;color:var(--text)">Relief Supply Distribution</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px"><i class="far fa-calendar"></i> Tomorrow, 08:00 AM &nbsp;|&nbsp; <i class="fas fa-map-marker-alt"></i> Central Hub</div>
        </div>
        <span class="badge badge-medium" style="font-size:10px">Req: Logistics</span>
    </div>
    <div style="padding:20px;border:1px solid var(--border);border-radius:10px;display:flex;align-items:center;gap:16px">
        <div style="width:44px;height:44px;border-radius:10px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;color:var(--accent);font-size:18px"><i class="fas fa-tower-broadcast"></i></div>
        <div style="flex:1">
            <div style="font-size:15px;font-weight:600;color:var(--text)">Comms Relay Setup</div>
            <div style="font-size:12px;color:var(--text-secondary);margin-top:2px"><i class="far fa-calendar"></i> Oct 12, 14:00 PM &nbsp;|&nbsp; <i class="fas fa-map-marker-alt"></i> North Ridge Tower</div>
        </div>
        <span class="badge badge-critical" style="font-size:10px">Req: Technical</span>
    </div>
</div>
@endsection
