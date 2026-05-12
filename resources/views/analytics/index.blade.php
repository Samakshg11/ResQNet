@extends('layouts.app')
@section('title', 'Analytics')
@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" />
<style>
    #analytics-map { 
        height: 480px; 
        width: 100%; 
        border-radius: 16px; 
        margin-bottom: 32px; 
        border: 1px solid var(--border); 
        z-index: 1; 
        background: #111; 
        box-shadow: inset 0 0 40px rgba(0,0,0,0.5);
    }
    
    /* Ensure Leaflet elements are visible */
    .leaflet-container { background: #0a0a0a !important; }
    .leaflet-popup-content-wrapper { background: #161616 !important; color: white !important; border: 1px solid #333; }
    .leaflet-popup-tip { background: #161616 !important; }
    
    .map-marker-pulse { width: 12px; height: 12px; background: var(--accent); border-radius: 50%; box-shadow: 0 0 10px var(--accent); }
    .map-marker-pulse::after { content: ''; position: absolute; inset: -8px; border: 2px solid var(--accent); border-radius: 50%; animation: pulse-ring 2s infinite; }
    @keyframes pulse-ring { 0% { transform: scale(0.5); opacity: 1; } 100% { transform: scale(2); opacity: 0; } }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1>Operational Intelligence</h1>
        <p>Geospatial analysis and platform performance metrics.</p>
    </div>
</div>

<div class="map-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
    <span class="section-title" style="margin-bottom:0">Live Situation Map</span>
    <div style="display:flex;gap:16px;align-items:center">
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--accent);margin-right:6px"></span> Critical</span>
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--yellow);margin-right:6px"></span> High</span>
        <span style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted)"><span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:var(--blue);margin-right:6px"></span> Normal</span>
    </div>
</div>

<div id="analytics-map"></div>

{{-- Stats Grid and other charts --}}
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

<div class="grid-2" style="margin-top: 32px;">
    <div class="card">
        <span class="section-title">SOS by Severity</span>
        @foreach($sosBySeverity as $severity => $count)
        @php $pct = $totalSOS > 0 ? round(($count/$totalSOS)*100) : 0; @endphp
        <div style="margin-bottom:16px">
            <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                <span style="font-size:12px;font-weight:600;text-transform:uppercase;color:var(--text-secondary)">{{ $severity }}</span>
                <span style="font-size:12px;color:var(--text-muted)">{{ $count }}</span>
            </div>
            <div style="height:6px;background:rgba(255,255,255,0.05);border-radius:3px;overflow:hidden">
                <div style="height:100%;width:{{ $pct }}%;background:{{ $severity==='critical'?'var(--accent)':($severity==='high'?'var(--yellow)':'var(--blue)') }}"></div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card">
        <span class="section-title">Resource Inventory</span>
        @foreach($resourcesByCategory as $cat => $total)
        <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid var(--border)">
            <span style="font-size:13px;color:var(--text-secondary)">{{ ucfirst(str_replace('_',' ',$cat)) }}</span>
            <span style="font-size:14px;font-weight:600;color:var(--green)">{{ number_format($total) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>
<script>
    (function() {
        function startMap() {
            console.log('ResQNet: Initializing Situation Map...');
            const el = document.getElementById('analytics-map');
            if (!el || el._leaflet_id) return;

            // 1. Initialize Map
            const map = L.map('analytics-map', { 
                zoomControl: true,
                attributionControl: true 
            }).setView([20.5937, 78.9629], 5);

            // 2. Add Tiles (CartoDB Dark Matter - Subdomains fixed)
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}.png', {
                subdomains: 'abcd',
                maxZoom: 19,
                attribution: '© CartoDB'
            }).addTo(map);

            // 3. Process Data
            const sosData = @json($sosMarkers);
            console.log('ResQNet: Loading ' + sosData.length + ' points of interest.');

            sosData.forEach(m => {
                if (m.latitude && m.longitude) {
                    const lat = parseFloat(m.latitude);
                    const lng = parseFloat(m.longitude);
                    if (isNaN(lat) || isNaN(lng)) return;

                    const color = m.severity === 'critical' ? '#e8735a' : (m.severity === 'high' ? '#f4be37' : '#4a90e2');
                    
                    L.circleMarker([lat, lng], {
                        radius: m.severity === 'critical' ? 7 : 5,
                        fillColor: color,
                        color: "#fff",
                        weight: 1,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(map).bindPopup(`
                        <div style="color:#000; font-family: sans-serif;">
                            <b style="color:${color}">${m.type.toUpperCase()}</b><br>
                            Status: ${m.status}<br>
                            Severity: ${m.severity}
                        </div>
                    `);
                }
            });

            // 4. Force layout fix
            setTimeout(() => { 
                map.invalidateSize(); 
                console.log('ResQNet: Map ready.');
            }, 800);
        }

        // Handle various loading states
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startMap);
        } else {
            startMap();
        }
    })();
</script>
@endsection




