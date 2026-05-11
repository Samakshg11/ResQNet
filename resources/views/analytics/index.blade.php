@extends('layouts.app')
@section('title', 'Analytics')
@section('content')
<div class="page-header">
    <div><h1>Analytics</h1><p>Platform performance metrics and operational intelligence.</p></div>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-card-header"><span class="stat-card-label">Rescue Rate</span><div class="stat-card-icon"><i class="fas fa-chart-line"></i></div></div>
        <div class="stat-card-value">{{ $totalSOS > 0 ? round(($rescueRate / $totalSOS) * 100) : 0 }}%</div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:{{ $totalSOS > 0 ? round(($rescueRate / $totalSOS) * 100) : 0 }}%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header"><span class="stat-card-label">Avg Response</span><div class="stat-card-icon"><i class="fas fa-clock"></i></div></div>
        <div class="stat-card-value">{{ $avgResponseTime ? round($avgResponseTime) : '—' }} <span class="unit">min</span></div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:45%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header"><span class="stat-card-label"><span class="live-dot"></span>Total Signals</span><div class="stat-card-icon"><i class="fas fa-circle-exclamation"></i></div></div>
        <div class="stat-card-value">{{ $totalSOS }}</div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:80%"></div></div>
    </div>
    <div class="stat-card">
        <div class="stat-card-header"><span class="stat-card-label">Resolved</span><div class="stat-card-icon"><i class="fas fa-check-double"></i></div></div>
        <div class="stat-card-value">{{ $rescueRate }}</div>
        <div class="stat-sparkline"><div class="stat-sparkline-fill" style="width:{{ $totalSOS > 0 ? round(($rescueRate / $totalSOS) * 100) : 0 }}%"></div></div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <span class="section-title">SOS by Severity</span>
        @foreach($sosBySeverity as $severity => $count)
        @php $pct = $totalSOS > 0 ? round(($count/$totalSOS)*100) : 0; @endphp
        <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <span style="font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-secondary)">{{ $severity }}</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $count }} ({{ $pct }}%)</span>
            </div>
            <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                <div style="height:100%;width:{{ $pct }}%;border-radius:3px;background:{{ $severity==='critical'?'var(--accent)':($severity==='high'?'var(--yellow)':($severity==='medium'?'var(--blue)':'var(--green)')) }}"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card">
        <span class="section-title">SOS by Type</span>
        @foreach($sosByType as $type => $count)
        @php $pct = $totalSOS > 0 ? round(($count/$totalSOS)*100) : 0; @endphp
        <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <span style="font-size:12px;font-weight:600;color:var(--text-secondary)">{{ str_replace('_',' ',ucfirst($type)) }}</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $count }}</span>
            </div>
            <div style="height:6px;background:var(--border);border-radius:3px;overflow:hidden">
                <div style="height:100%;width:{{ $pct }}%;border-radius:3px;background:var(--accent)"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card">
        <span class="section-title">Agencies by Type</span>
        @foreach($agenciesByType as $type => $count)
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
            <span style="font-size:13px;font-weight:500;color:var(--text-secondary)">{{ ucfirst(str_replace('_',' ',$type)) }}</span>
            <span style="font-size:14px;font-weight:600;color:var(--accent)">{{ $count }}</span>
        </div>
        @endforeach
    </div>
    <div class="card">
        <span class="section-title">Resource Inventory</span>
        @foreach($resourcesByCategory as $cat => $total)
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
            <span style="font-size:13px;font-weight:500;color:var(--text-secondary)">{{ ucfirst(str_replace('_',' ',$cat)) }}</span>
            <span style="font-size:14px;font-weight:600;color:var(--green)">{{ number_format($total) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection
