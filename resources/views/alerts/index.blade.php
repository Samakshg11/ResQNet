@extends('layouts.app')
@section('title', 'Alerts')
@section('content')
<div class="page-header">
    <div><h1>Emergency Alerts</h1><p>System-wide broadcasts and notifications.</p></div>
    <a href="{{ route('alerts.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-bullhorn"></i> Broadcast</a>
</div>
<div class="card" style="padding:0">
    @forelse($alerts as $alert)
    <div style="padding:20px 24px;border-bottom:1px solid var(--border);display:flex;gap:16px;align-items:flex-start;transition:background .2s">
        <div style="width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;background:{{ $alert->type === 'emergency' ? 'var(--accent-soft)' : ($alert->type === 'warning' ? 'var(--yellow-soft)' : 'var(--blue-soft)') }};color:{{ $alert->type === 'emergency' ? 'var(--accent)' : ($alert->type === 'warning' ? 'var(--yellow)' : 'var(--blue)') }}">
            <i class="fas {{ $alert->type === 'emergency' ? 'fa-circle-exclamation' : ($alert->type === 'warning' ? 'fa-triangle-exclamation' : 'fa-info-circle') }}"></i>
        </div>
        <div style="flex:1">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
                <span class="badge badge-{{ $alert->type === 'emergency' ? 'critical' : ($alert->type === 'warning' ? 'high' : 'medium') }}">{{ strtoupper($alert->type) }}</span>
                <span style="font-size:11px;color:var(--text-muted)">{{ $alert->created_at->diffForHumans() }}</span>
            </div>
            <h3 style="font-size:15px;font-weight:600;color:var(--text);margin-bottom:4px">{{ $alert->title }}</h3>
            <p style="font-size:13px;color:var(--text-secondary);line-height:1.6">{{ Str::limit($alert->message, 140) }}</p>
        </div>
    </div>
    @empty
    <p style="padding:40px;text-align:center;color:var(--text-muted)">No alerts.</p>
    @endforelse
    <div style="padding:16px">{{ $alerts->links() }}</div>
</div>
@endsection
