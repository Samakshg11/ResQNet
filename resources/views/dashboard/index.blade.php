@extends('layouts.app')
@section('title', 'Command Center')
@section('content')

{{-- Critical Alert Banner --}}
@if($activeDisasters->where('severity', 'critical')->first())
@php $critical = $activeDisasters->where('severity', 'critical')->first(); @endphp
<div class="alert-banner">
    <div class="alert-banner-left">
        <span class="live-dot"></span>
        <span class="alert-banner-text">CRITICAL: {{ $critical->title }}</span>
    </div>
    <a href="{{ route('disasters.show', $critical->id) }}" class="alert-banner-action">View Details →</a>
</div>
@endif

{{-- Stat Cards --}}
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-label">Active Disasters</span>
            <div class="stat-card-icon"><i class="fas fa-hurricane"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['active_disasters'] }} <span class="unit" style="color:var(--accent)">↗+{{ rand(1,3) }}</span></div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:{{ min(100, $stats['active_disasters'] * 25) }}%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-label"><span class="live-dot"></span>SOS Signals</span>
            <div class="stat-card-icon"><i class="fas fa-circle-exclamation"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['total_sos'] }} <span class="unit" style="color:var(--accent)">↗+{{ rand(10,20) }}%</span></div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:{{ $stats['total_sos'] > 0 ? min(100, ($stats['pending_sos']/$stats['total_sos'])*100) : 0 }}%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-label">Units Deployed</span>
            <div class="stat-card-icon"><i class="fas fa-building-shield"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['deployed_agencies'] + $stats['total_agencies'] }} <span class="unit">Active</span></div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:68%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-label">Resource Capacity</span>
            <div class="stat-card-icon"><i class="fas fa-boxes-stacked"></i></div>
        </div>
        @php
            $totalRes = \App\Models\Resource::sum('total_quantity');
            $availRes = \App\Models\Resource::sum('available_quantity');
            $capPct = $totalRes > 0 ? round(($availRes / $totalRes) * 100) : 0;
        @endphp
        <div class="stat-card-value">{{ $capPct }}% <span class="unit">Stable</span></div>
        <div style="display:flex;gap:4px;margin-top:14px">
            <div style="flex:{{ $capPct }};height:8px;background:var(--accent);border-radius:3px 0 0 3px"></div>
            <div style="flex:{{ 100 - $capPct }};height:8px;background:var(--border);border-radius:0 3px 3px 0"></div>
        </div>
    </div>
</div>

{{-- Rescue Activity + Active Disasters --}}
<div class="grid-2" style="margin-bottom:28px">
    <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px">
            <span class="section-title" style="margin-bottom:0">Rescue Activity</span>
            <span style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--accent);cursor:pointer"><i class="fas fa-sliders"></i> Filters</span>
        </div>
        {{-- Bar chart visualization --}}
        <div style="display:flex;align-items:flex-end;gap:12px;height:180px;padding-top:20px">
            @php
                $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
                $vals = [18, 35, 28, 42, 55, 48, 65];
            @endphp
            @foreach($days as $i => $day)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:8px">
                <div style="width:100%;display:flex;gap:3px;align-items:flex-end;height:140px">
                    <div style="flex:1;height:{{ $vals[$i] * 0.6 }}%;background:var(--accent);opacity:.4;border-radius:3px 3px 0 0"></div>
                    <div style="flex:1;height:{{ $vals[$i] }}%;background:var(--accent);opacity:.7;border-radius:3px 3px 0 0"></div>
                </div>
                <span style="font-size:10px;color:var(--muted,var(--text-muted))">{{ $day }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <div class="card">
        <span class="section-title">Active Disasters</span>
        @forelse($activeDisasters as $d)
        <a href="{{ route('disasters.show', $d->id) }}" style="display:block;padding:16px 0;border-bottom:1px solid var(--border)">
            <div style="display:flex;align-items:center;justify-content:space-between">
                <div>
                    <div style="font-size:15px;font-weight:600;color:var(--text);margin-bottom:4px">{{ Str::limit($d->title, 30) }}</div>
                    <div style="font-size:12px;color:var(--text-secondary,var(--text2))"><i class="fas fa-map-marker-alt" style="margin-right:4px"></i>{{ $d->type === 'cyclone' ? 'Gujarat Coast' : ($d->type === 'flood' ? 'Assam Basin' : ($d->type === 'earthquake' ? 'Bihar Border' : 'Mumbai Docks')) }}</div>
                </div>
                <span class="badge badge-{{ $d->severity === 'critical' ? 'extreme' : $d->severity }}">{{ strtoupper($d->severity) }}</span>
            </div>
        </a>
        @empty
        <p style="color:var(--text-muted);font-size:13px;padding:20px 0">No active disasters.</p>
        @endforelse
    </div>
</div>

{{-- Recent SOS Table --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <span class="section-title" style="margin-bottom:0">Recent SOS Signals</span>
        <a href="{{ route('sos.index') }}" class="btn btn-ghost btn-sm">View All →</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Victim</th><th>Type</th><th>Severity</th><th>Status</th><th>Agency</th><th>Time</th><th></th></tr></thead>
            <tbody>
            @forelse($recentSOS->take(8) as $sos)
            <tr>
                <td style="color:var(--text);font-weight:500">{{ $sos->victim_name ?? 'Anonymous' }}</td>
                <td>{{ str_replace('_', ' ', ucfirst($sos->type)) }}</td>
                <td><span class="badge badge-{{ $sos->severity }}">{{ strtoupper($sos->severity) }}</span></td>
                <td><span class="badge badge-{{ $sos->status }}">{{ strtoupper(str_replace('_', ' ', $sos->status)) }}</span></td>
                <td>{{ Str::limit($sos->assignedAgency->name ?? '—', 20) }}</td>
                <td style="font-size:11px;color:var(--text-muted)">{{ $sos->created_at->diffForHumans() }}</td>
                <td><a href="{{ route('sos.show', $sos->id) }}" class="btn btn-ghost btn-sm">View</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted)">No SOS requests.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
