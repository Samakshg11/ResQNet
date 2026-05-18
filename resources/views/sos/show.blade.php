@extends('layouts.app')
@section('title', 'SOS Detail')
@section('content')
<div class="page-header"><div><h1>SOS Signal Detail</h1><p>Signal ID: {{ substr($sos->id, 0, 12) }}</p></div></div>
<div class="grid-2">
    <div class="card">
        <span class="section-title">Signal Information</span>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:16px">
            <div><span class="form-label">Status</span><br><span class="badge badge-{{ $sos->status }}">{{ strtoupper(str_replace('_',' ',$sos->status)) }}</span></div>
            <div><span class="form-label">Severity</span><br><span class="badge badge-{{ $sos->severity }}">{{ strtoupper($sos->severity) }}</span></div>
            <div><span class="form-label">Type</span><br><span style="font-size:14px">{{ str_replace('_',' ',ucfirst($sos->type)) }}</span></div>
            <div><span class="form-label">Victims</span><br><span style="font-size:14px">{{ $sos->victim_count }} person(s)</span></div>
            <div><span class="form-label">Name</span><br><span style="font-size:14px;color:var(--text)">{{ $sos->victim_name ?? 'N/A' }}</span></div>
            <div><span class="form-label">Phone</span><br><span style="font-size:14px">{{ $sos->victim_phone ?? 'N/A' }}</span></div>
            <div><span class="form-label">Coordinates</span><br><span style="font-family:monospace;font-size:13px">{{ $sos->latitude }}, {{ $sos->longitude }}</span></div>
            <div><span class="form-label">Timestamp</span><br><span style="font-size:14px">{{ $sos->created_at->format('M d, Y H:i') }}</span></div>
        </div>
        @if($sos->message)<div style="margin-top:20px"><span class="form-label">Message</span><p style="margin-top:6px;font-size:13px;color:var(--text-secondary);line-height:1.6">{{ $sos->message }}</p></div>@endif
    </div>
    <div>
        @if($sos->status === 'pending')
        <div class="card" style="margin-bottom:20px">
            <span class="section-title">Assign Unit</span>
            <form method="POST" action="{{ route('sos.assign', $sos->id) }}">@csrf
                <div class="form-group"><select name="agency_id" class="form-control" required><option value="">Select agency...</option>@foreach($agencies as $a)<option value="{{ $a->id }}">{{ $a->name }}</option>@endforeach</select></div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-check"></i> Assign</button>
            </form>
        </div>
        @endif
        <div class="card" style="margin-bottom:20px">
            <span class="section-title">Update Status</span>
            @php
                $updateRoute = auth()->user()->role === 'agency_admin' ? 'agency.sos.updateStatus' : 'sos.updateStatus';
            @endphp
            <form method="POST" action="{{ route($updateRoute, $sos->id) }}">@csrf @method('PATCH')
                <div class="form-group"><select name="status" class="form-control" required>@foreach(['pending','assigned','dispatched','en_route','resolved','cancelled'] as $s)<option value="{{ $s }}" {{ $sos->status===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>@endforeach</select></div>
                <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-save"></i> Update</button>
            </form>
        </div>
        @if($sos->assignedAgency)
        <div class="card">
            <span class="section-title">Assigned Unit</span>
            <div style="font-weight:600;font-size:16px;color:var(--text)">{{ $sos->assignedAgency->name }}</div>
            <div style="font-size:13px;color:var(--text-secondary);margin-top:4px">{{ $sos->assignedAgency->getTypeLabel() }} · {{ $sos->assignedAgency->region }}</div>
            @if($sos->assigned_at)<div style="font-size:12px;color:var(--text-muted);margin-top:8px">Assigned: {{ $sos->assigned_at->diffForHumans() }}</div>@endif
        </div>
        @endif
    </div>
</div>
@endsection
