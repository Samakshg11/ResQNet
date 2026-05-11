@extends('layouts.app')
@section('title', $disaster->title)
@section('content')
<div class="grid-2">
    <div class="card">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px">
            <span style="font-size:44px">{{ $disaster->getTypeIcon() }}</span>
            <div>
                <h2 style="font-family:var(--serif);font-size:24px;font-weight:400;color:var(--text)">{{ $disaster->title }}</h2>
                <div style="display:flex;gap:8px;margin-top:8px">
                    <span class="badge badge-{{ $disaster->severity }}">{{ strtoupper($disaster->severity) }}</span>
                    <span class="badge badge-{{ $disaster->status }}">{{ strtoupper($disaster->status) }}</span>
                </div>
            </div>
        </div>
        <p style="font-size:14px;color:var(--text-secondary);line-height:1.7;margin-bottom:24px">{{ $disaster->description }}</p>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:24px">
            <div class="stat-card" style="padding:16px"><div class="stat-card-label">Affected</div><div style="font-size:24px;font-weight:300;color:var(--text);margin-top:8px">{{ number_format($disaster->estimated_affected) }}</div></div>
            <div class="stat-card" style="padding:16px"><div class="stat-card-label">Casualties</div><div style="font-size:24px;font-weight:300;color:var(--accent);margin-top:8px">{{ $disaster->confirmed_casualties }}</div></div>
            <div class="stat-card" style="padding:16px"><div class="stat-card-label">Rescued</div><div style="font-size:24px;font-weight:300;color:var(--green);margin-top:8px">{{ number_format($disaster->rescued_count) }}</div></div>
        </div>
        <form method="POST" action="{{ route('disasters.update', $disaster->id) }}">@csrf @method('PATCH')
            <div class="form-row">
                <div class="form-group"><label class="form-label">Status</label><select name="status" class="form-control">@foreach(['monitoring','active','contained','resolved'] as $s)<option value="{{ $s }}" {{ $disaster->status===$s?'selected':'' }}>{{ ucfirst($s) }}</option>@endforeach</select></div>
                <div class="form-group"><label class="form-label">Rescued Count</label><input type="number" name="rescued_count" class="form-control" value="{{ $disaster->rescued_count }}"></div>
            </div>
            <button type="submit" class="btn btn-ghost btn-sm"><i class="fas fa-save"></i> Update</button>
        </form>
    </div>
    <div class="card">
        <span class="section-title">Related SOS Signals ({{ $sosRequests->count() }})</span>
        @forelse($sosRequests as $sos)
        <a href="{{ route('sos.show', $sos->id) }}" style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--border)">
            <div>
                <span style="font-weight:500;font-size:14px;color:var(--text)">{{ $sos->victim_name ?? 'Anonymous' }}</span>
                <div style="font-size:12px;color:var(--text-muted);margin-top:2px">{{ str_replace('_',' ',ucfirst($sos->type)) }} · {{ $sos->victim_count }} victims</div>
            </div>
            <span class="badge badge-{{ $sos->severity }}">{{ strtoupper($sos->severity) }}</span>
        </a>
        @empty
        <p style="color:var(--text-muted);font-size:13px;padding:20px 0">No linked SOS signals.</p>
        @endforelse
    </div>
</div>
@endsection
