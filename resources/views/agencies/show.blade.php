@extends('layouts.app')
@section('title', $agency->name)
@section('content')
<div class="page-header"><h1>{{ $agency->name }}</h1><p>{{ $agency->getTypeLabel() }} · {{ $agency->region }}, {{ $agency->state }}</p></div>
<div class="grid-2">
    <div class="card">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
            <div style="width:56px;height:56px;border-radius:14px;background:var(--accent-soft);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:700;color:var(--accent)">{{ substr($agency->name,0,1) }}</div>
            <div><span class="badge badge-{{ $agency->status }}">{{ ucfirst($agency->status) }}</span></div>
        </div>
        @if($agency->description)<p style="font-size:14px;color:var(--text-secondary);line-height:1.7;margin-bottom:20px">{{ $agency->description }}</p>@endif
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div><span class="form-label">Registration</span><br><span style="font-size:13px">{{ $agency->registration_number }}</span></div>
            <div><span class="form-label">Contact</span><br><span style="font-size:13px">{{ $agency->contact_phone }}</span></div>
            <div><span class="form-label">Email</span><br><span style="font-size:13px">{{ $agency->contact_email }}</span></div>
            <div><span class="form-label">Teams</span><br><span style="font-size:13px">{{ $agency->total_teams }}</span></div>
            <div><span class="form-label">Success Rate</span><br><span style="font-size:16px;font-weight:600;color:var(--green)">{{ $agency->rescue_success_rate }}%</span></div>
            <div><span class="form-label">Address</span><br><span style="font-size:13px">{{ $agency->address }}</span></div>
        </div>
    </div>
    <div>
        <div class="card" style="margin-bottom:20px">
            <span class="section-title">Resources ({{ $agency->resources->count() }})</span>
            @forelse($agency->resources as $r)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                <div><span style="font-size:14px;font-weight:500;color:var(--text)">{{ $r->name }}</span><div style="font-size:11px;color:var(--text-muted)">{{ ucfirst(str_replace('_',' ',$r->category)) }}</div></div>
                <div style="text-align:right"><span style="color:var(--green);font-weight:600;font-size:14px">{{ $r->available_quantity }}</span><span style="color:var(--text-muted);font-size:12px">/{{ $r->total_quantity }}</span></div>
            </div>
            @empty<p style="color:var(--text-muted);font-size:13px">No resources.</p>@endforelse
        </div>
        <div class="card">
            <span class="section-title">Volunteers ({{ $agency->volunteers->count() }})</span>
            @forelse($agency->volunteers as $v)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid var(--border)">
                <div><span style="font-size:14px;font-weight:500;color:var(--text)">{{ $v->user->name }}</span><div style="font-size:11px;color:var(--text-muted)">{{ implode(', ', $v->skills ?? []) }}</div></div>
                <span class="badge badge-{{ $v->availability === 'available' ? 'available' : 'pending' }}">{{ ucfirst(str_replace('_',' ',$v->availability)) }}</span>
            </div>
            @empty<p style="color:var(--text-muted);font-size:13px">No volunteers.</p>@endforelse
        </div>
    </div>
</div>
@endsection
