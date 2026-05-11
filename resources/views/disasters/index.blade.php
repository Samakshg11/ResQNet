@extends('layouts.app')
@section('title', 'Disaster Map')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    /* Override layout for full-screen map */
    .main-content { padding: 0 !important; display: flex; height: calc(100vh - 60px); }
    .map-container { flex: 1; position: relative; }
    #map { width: 100%; height: 100%; background: #0a0a0a; }
    
    /* Live Feed Sidebar */
    .live-feed-sidebar { width: 340px; background: #161616; border-left: 1px solid var(--border); display: flex; flex-direction: column; }
    .live-feed-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .live-feed-title { font-size: 16px; font-weight: 600; color: var(--text); display: flex; align-items: center; gap: 8px; }
    .live-feed-content { flex: 1; overflow-y: auto; padding: 16px; display: flex; flex-direction: column; gap: 12px; }
    
    .feed-card { background: #0a0a0a; border: 1px solid var(--border); border-left: 3px solid var(--accent); border-radius: 8px; padding: 16px; }
    .feed-card.warning { border-left-color: var(--yellow); }
    .feed-card.info { border-left-color: var(--blue); }
    
    .feed-card-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .feed-card-type { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text); }
    .feed-card-time { font-size: 11px; color: var(--text-muted); }
    .feed-card-body { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }

    /* Map Overlay UI */
    .map-overlay-top { position: absolute; top: 20px; left: 20px; z-index: 1000; }
    .map-overlay-bottom { position: absolute; bottom: 30px; left: 20px; z-index: 1000; }
    .map-overlay-controls { position: absolute; bottom: 30px; right: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 8px; }
    
    .map-btn { width: 40px; height: 40px; background: #161616; border: 1px solid var(--border); border-radius: 8px; color: var(--text); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
    .map-btn:hover { background: #1e1e1e; border-color: var(--text-muted); }
    
    .dispatch-btn { background: linear-gradient(135deg, var(--accent), #c45040); color: #0a0a0a; padding: 16px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; border: none; cursor: pointer; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 20px rgba(232,115,90,0.3); transition: transform 0.2s; }
    .dispatch-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 25px rgba(232,115,90,0.4); }

    /* Leaflet Dark Theme Adjustments */
    .leaflet-popup-content-wrapper, .leaflet-popup-tip { background: #161616; color: var(--text); border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .leaflet-container a.leaflet-popup-close-button { color: var(--text-muted); }
    .leaflet-popup-content { margin: 16px; }
    
    .map-marker-pulse { width: 14px; height: 14px; background: var(--accent); border-radius: 50%; position: relative; box-shadow: 0 0 15px var(--accent); }
    .map-marker-pulse::after { content: ''; position: absolute; inset: -10px; border: 2px solid var(--accent); border-radius: 50%; animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
    @keyframes pulse-ring { 0% { transform: scale(0.8); opacity: 1; } 100% { transform: scale(2.5); opacity: 0; } }
</style>
@endsection
@section('content')
<div class="map-container">
    <div id="map"></div>
    
    <div class="map-overlay-bottom">
        <button class="dispatch-btn">
            <i class="fas fa-bullhorn"></i> Emergency Dispatch
        </button>
    </div>
    
    <div class="map-overlay-controls">
        <button class="map-btn" onclick="map.zoomIn()"><i class="fas fa-plus"></i></button>
        <button class="map-btn" onclick="map.zoomOut()"><i class="fas fa-minus"></i></button>
        <button class="map-btn" onclick="recenterMap()"><i class="fas fa-crosshairs"></i></button>
        <button class="map-btn" style="margin-top: 8px"><i class="fas fa-layer-group"></i></button>
    </div>
</div>

<div class="live-feed-sidebar">
    <div class="live-feed-header">
        <div class="live-feed-title">
            <i class="fas fa-tower-broadcast" style="color:var(--accent)"></i> Live Feed
            <span class="live-dot" style="margin-left:8px"></span>
        </div>
    </div>
    <div class="live-feed-content">
        @forelse($sosRequests as $sos)
        <div class="feed-card {{ $sos->severity === 'critical' ? '' : ($sos->severity === 'high' ? 'warning' : 'info') }}">
            <div class="feed-card-header">
                <span class="feed-card-type">SOS Signal</span>
                <span class="feed-card-time">{{ $sos->created_at->diffForHumans(null, true, true) }}</span>
            </div>
            <div class="feed-card-body">
                {{ str_replace('_', ' ', ucfirst($sos->type)) }} reported by {{ $sos->victim_name ?? 'citizen' }}. {{ $sos->victim_count }} victim(s) involved.
                @if($sos->message) <br><br> "{{ Str::limit($sos->message, 60) }}" @endif
            </div>
        </div>
        @empty
        <p style="color:var(--text-muted); font-size:13px; text-align:center; margin-top:20px;">No recent activity.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize Map with Dark theme tiles (CartoDB Dark Matter)
    const map = L.map('map', { zoomControl: false }).setView([22.5, 79.0], 5);
    
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
        subdomains: 'abcd',
        maxZoom: 20
    }).addTo(map);

    // Custom Icons
    const criticalIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div class="map-marker-pulse"></div>',
        iconSize: [14, 14],
        iconAnchor: [7, 7]
    });

    const standardIcon = L.divIcon({
        className: 'custom-div-icon',
        html: '<div style="width:10px;height:10px;background:var(--yellow);border-radius:50%;box-shadow:0 0 10px var(--yellow);"></div>',
        iconSize: [10, 10],
        iconAnchor: [5, 5]
    });

    // Load Disasters
    const disasters = @json($disasters->items() ?? $disasters);
    disasters.forEach(d => {
        if(d.latitude && d.longitude) {
            const icon = d.severity === 'critical' ? criticalIcon : standardIcon;
            const marker = L.marker([d.latitude, d.longitude], { icon: icon }).addTo(map);
            
            const popupHtml = `
                <div style="min-width:200px">
                    <div style="font-size:10px;font-weight:700;color:var(--text-muted);text-transform:uppercase;margin-bottom:4px;letter-spacing:1px">${d.severity}</div>
                    <div style="font-size:15px;font-weight:600;color:var(--text);margin-bottom:8px">${d.title}</div>
                    <div style="font-size:12px;color:var(--text-secondary);margin-bottom:12px">${d.description ? d.description.substring(0,60)+'...' : ''}</div>
                    <div style="display:flex;gap:8px">
                        <a href="/disasters/${d.id}" style="flex:1;text-align:center;padding:6px;background:var(--border);color:var(--text);border-radius:4px;font-size:11px;font-weight:600;text-decoration:none;">Details</a>
                        <button style="flex:1;padding:6px;background:var(--accent);color:#0a0a0a;border:none;border-radius:4px;font-size:11px;font-weight:600;cursor:pointer;">Dispatch</button>
                    </div>
                </div>
            `;
            marker.bindPopup(popupHtml);
        }
    });

    // Load SOS Requests
    const sosRequests = @json($sosRequests ?? []);
    sosRequests.forEach(s => {
        if(s.latitude && s.longitude) {
            const marker = L.marker([s.latitude, s.longitude], { icon: criticalIcon }).addTo(map);
        }
    });

    // Real-time updates via Laravel Echo
    document.addEventListener('DOMContentLoaded', function() {
        if (window.Echo) {
            window.Echo.channel('emergency-channel')
                .listen('.new.sos', (e) => {
                    // Add marker to map
                    if(e.sos.latitude && e.sos.longitude) {
                        L.marker([e.sos.latitude, e.sos.longitude], { icon: criticalIcon })
                            .addTo(map)
                            .bindPopup(`<div style="min-width:150px">
                                <div style="font-size:10px;font-weight:700;color:var(--accent);margin-bottom:4px">NEW SIGNAL: ${e.sos.type}</div>
                                <div style="font-size:12px;color:var(--text-secondary)">Victims: ${e.sos.victim_count}</div>
                            </div>`)
                            .openPopup();
                        map.setView([e.sos.latitude, e.sos.longitude], 8);
                    }
                    
                    // Add card to live feed
                    const feedList = document.querySelector('.live-feed-content');
                    if (feedList) {
                        const noActivity = feedList.querySelector('p');
                        if (noActivity) noActivity.remove();
                        
                        const newCard = document.createElement('div');
                        newCard.className = 'feed-card fade-up';
                        newCard.innerHTML = `
                            <div class="feed-card-header">
                                <span class="feed-card-type" style="color:var(--accent)">NEW SOS SIGNAL</span>
                                <span class="feed-card-time">Just now</span>
                            </div>
                            <div class="feed-card-body">
                                ${e.sos.type.replace('_', ' ')} reported by ${e.sos.victim_name || 'citizen'}. ${e.sos.victim_count} victim(s) involved.
                                ${e.sos.message ? `<br><br>"${e.sos.message}"` : ''}
                            </div>
                        `;
                        feedList.insertBefore(newCard, feedList.firstChild);
                    }
                });
        }
    });

    function recenterMap() {
        map.setView([22.5, 79.0], 5);
    }
</script>
@endsection
