@extends('layouts.app')
@section('title', 'Analytics')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #analytics-map { height: 400px; width: 100%; border-radius: 12px; margin-bottom: 32px; border: 1px solid var(--border); z-index: 1; }
    .map-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
</style>
@endsection
@section('content')
<div class="page-header">
    <div><h1>Analytics</h1><p>Platform performance metrics and operational intelligence.</p></div>
</div>

<div class="map-header">
    <span class="section-title" style="margin-bottom:0">Live Situation Map</span>
    <div style="display:flex;gap:12px;align-items:center">
        <span style="font-size:12px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--accent);margin-right:4px"></span> Critical</span>
        <span style="font-size:12px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--yellow);margin-right:4px"></span> Warning</span>
    </div>
</div>
<div id="analytics-map"></div>

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

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('analytics-map', { zoomControl: false }).setView([22.5, 79.0], 5);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        const markers = @json($sosMarkers);
        
        markers.forEach(m => {
            if (m.latitude && m.longitude) {
                const color = m.severity === 'critical' ? 'var(--accent)' : (m.severity === 'high' ? 'var(--yellow)' : 'var(--blue)');
                const markerIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="width:10px;height:10px;background:${color};border-radius:50%;box-shadow:0 0 10px ${color};"></div>`,
                    iconSize: [10, 10],
                    iconAnchor: [5, 5]
                });

                L.marker([m.latitude, m.longitude], { icon: markerIcon }).addTo(map)
                 .bindPopup(`<div style="font-size:12px;font-weight:600;color:#0a0a0a">${m.type.toUpperCase()}</div><div style="font-size:10px;color:#666">${m.status}</div>`);
            }
        });
        
        L.control.zoom({ position: 'bottomright' }).addTo(map);
    });
</script>
@endsection
