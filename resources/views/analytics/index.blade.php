@extends('layouts.app')
@section('title', 'Analytics')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #analytics-map { height: 440px; width: 100%; border-radius: 16px; margin-bottom: 32px; border: 1px solid var(--border); z-index: 1; background: #0a0a0a; }
    .map-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    
    /* Premium Map UI */
    .leaflet-popup-content-wrapper, .leaflet-popup-tip { background: #161616; color: var(--text); border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .leaflet-container a.leaflet-popup-close-button { color: var(--text-muted); }
    .leaflet-popup-content { margin: 16px; font-family: var(--sans); }
    
    .map-marker-dot { width: 10px; height: 10px; border-radius: 50%; position: relative; }
    .map-marker-dot.critical { background: var(--accent); box-shadow: 0 0 15px var(--accent); }
    .map-marker-dot.high { background: var(--yellow); box-shadow: 0 0 15px var(--yellow); }
    .map-marker-dot.medium { background: var(--blue); box-shadow: 0 0 15px var(--blue); }
    
    .map-marker-dot.critical::after { content: ''; position: absolute; inset: -6px; border: 1.5px solid var(--accent); border-radius: 50%; animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
    @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2.5); opacity: 0; } }
</style>
@endsection
@section('content')
<div class="page-header">
    <div><h1>Operational Intelligence</h1><p>Geospatial analysis and platform performance metrics.</p></div>
</div>

<div class="map-header">
    <span class="section-title" style="margin-bottom:0">Live Situation Map</span>
    <div style="display:flex;gap:16px;align-items:center">
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--accent);margin-right:6px"></span> Critical</span>
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--yellow);margin-right:6px"></span> High</span>
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--blue);margin-right:6px"></span> Other</span>
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
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        const markers = @json($sosMarkers);
        
        markers.forEach(m => {
            if (m.latitude && m.longitude) {
                const severityClass = m.severity === 'critical' ? 'critical' : (m.severity === 'high' ? 'high' : 'medium');
                const markerIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="map-marker-dot ${severityClass}"></div>`,
                    iconSize: [10, 10],
                    iconAnchor: [5, 5]
                });

                L.marker([parseFloat(m.latitude), parseFloat(m.longitude)], { icon: markerIcon }).addTo(map)
                 .bindPopup(`
                    <div style="min-width:140px">
                        <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;letter-spacing:1px">${m.severity}</div>
                        <div style="font-size:14px;font-weight:600;color:var(--text);margin-bottom:4px">${m.type.replace('_',' ').toUpperCase()}</div>
                        <div style="font-size:12px;color:var(--accent);font-weight:600">${m.status.toUpperCase()}</div>
                    </div>
                 `);
            }
        });

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                map.setView([position.coords.latitude, position.coords.longitude], 6);
            }, function() {
                // Fallback if location access denied
                map.setView([22.5, 79.0], 5);
            });
        }
        
        L.control.zoom({ position: 'bottomright' }).addTo(map);
    });
</script>
@endsection

