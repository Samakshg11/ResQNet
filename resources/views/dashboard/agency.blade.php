@extends('layouts.app')
@section('title', 'Agency Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1>Agency Command Center</h1>
        <p>Manage your active teams, missions, and resources.</p>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-label">Active Missions</div>
            <div class="stat-card-icon"><i class="fas fa-truck-fast"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['active_missions'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-label">Resolved Missions</div>
            <div class="stat-card-icon"><i class="fas fa-check-double"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['resolved_missions'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-label">Active Volunteers</div>
            <div class="stat-card-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['total_volunteers'] }}</div>
    </div>
</div>

<h2 class="section-title" style="margin-top:40px;">Active Deployments</h2>
@if($activeSos->count() > 0)
    <div class="table-wrap card" style="padding:0; overflow:hidden;">
        <table>
            <thead>
                <tr>
                    <th>SOS ID</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Status</th>
                    <th>Location</th>
                    <th>Assigned</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activeSos as $sos)
                <tr>
                    <td style="font-family:monospace;">#{{ str_pad($sos->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $sos->type)) }}</td>
                    <td><span class="badge badge-{{ $sos->severity }}">{{ $sos->severity }}</span></td>
                    <td><span class="badge badge-{{ $sos->status }}">{{ $sos->status }}</span></td>
                    <td>{{ number_format($sos->latitude, 4) }}, {{ number_format($sos->longitude, 4) }}</td>
                    <td>{{ $sos->assigned_at ? $sos->assigned_at->diffForHumans() : '-' }}</td>
                    <td>
                        <a href="{{ route('agency.sos.show', $sos->id) }}" class="btn btn-outline btn-sm">View Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card" style="text-align:center; padding:48px;">
        <i class="fas fa-check-circle" style="font-size:48px; color:var(--green); margin-bottom:16px; opacity:0.5;"></i>
        <h3 style="font-size:18px; margin-bottom:8px;">No Active Missions</h3>
        <p style="color:var(--text-secondary); font-size:14px;">Your agency has no active SOS assignments. Monitor the SOS feed for new emergencies.</p>
        <a href="{{ route('sos.feed') }}" class="btn btn-primary" style="margin-top:20px;">Open SOS Feed</a>
    </div>
@endif
@endsection
