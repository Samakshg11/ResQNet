@extends('layouts.app')
@section('title', 'Volunteer Dashboard')
@section('content')
<div class="page-header">
    <div>
        <h1>Volunteer Hub</h1>
        <p>Welcome back, {{ $user->name }}. You are actively volunteering with {{ $volunteer->agency->user->name ?? 'an agency' }}.</p>
    </div>
</div>

<div class="stat-grid" style="grid-template-columns: 1fr 1fr;">
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-label">Total Missions</div>
            <div class="stat-card-icon"><i class="fas fa-route"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['total_missions'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header">
            <div class="stat-card-label">Hours Logged</div>
            <div class="stat-card-icon"><i class="fas fa-clock"></i></div>
        </div>
        <div class="stat-card-value">{{ $stats['hours_logged'] }} <span class="unit">hrs</span></div>
    </div>
</div>

<h2 class="section-title">Active Deployments</h2>
@if($activeMissions->count() > 0)
    <div class="grid-2">
    @foreach($activeMissions as $mission)
        <div class="card">
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                <div>
                    <h3 style="font-size:16px; margin-bottom:4px;">{{ ucwords(str_replace('_', ' ', $mission->type)) }}</h3>
                    <p style="font-size:12px; color:var(--text-secondary);">ID: #{{ str_pad($mission->id, 5, '0', STR_PAD_LEFT) }}</p>
                </div>
                <span class="badge badge-{{ $mission->status }}">{{ $mission->status }}</span>
            </div>
            
            <div style="margin-bottom:16px; font-size:13px; color:var(--text-secondary);">
                <i class="fas fa-map-marker-alt"></i> {{ number_format($mission->latitude, 4) }}, {{ number_format($mission->longitude, 4) }}
                @if($mission->disaster)
                <br><br><i class="fas fa-bolt"></i> Related: {{ $mission->disaster->title }}
                @endif
            </div>

            <div style="display:flex; gap:12px; align-items:center;">
                <form method="POST" action="{{ route('volunteer.sos.updateStatus', $mission->id) }}" style="flex:1;">
                    @csrf
                    @method('PATCH')
                    @if($mission->status === 'assigned')
                        <input type="hidden" name="status" value="dispatched">
                        <button class="btn btn-primary" style="width:100%; justify-content:center;">Acknowledge & Dispatch</button>
                    @elseif($mission->status === 'dispatched')
                        <input type="hidden" name="status" value="en_route">
                        <button class="btn btn-primary" style="width:100%; justify-content:center;">Mark En Route</button>
                    @elseif($mission->status === 'en_route')
                        <input type="hidden" name="status" value="resolved">
                        <button class="btn btn-success" style="width:100%; justify-content:center;">Resolve Mission</button>
                    @endif
                </form>
            </div>
        </div>
    @endforeach
    </div>
@else
    <div class="card" style="text-align:center; padding:48px;">
        <i class="fas fa-check-circle" style="font-size:48px; color:var(--green); margin-bottom:16px; opacity:0.5;"></i>
        <h3 style="font-size:18px; margin-bottom:8px;">No Active Deployments</h3>
        <p style="color:var(--text-secondary); font-size:14px;">You currently have no active missions assigned. Stand by for dispatch.</p>
    </div>
@endif

@endsection
