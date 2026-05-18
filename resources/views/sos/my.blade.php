<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResQNet Emergency Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root {
            --bg: #0a0a0a;
            --card: #161616;
            --border: #1e1e1e;
            --text: #e8e0d8;
            --text2: #8a7f75;
            --accent: #e8735a;
            --peach: #f0c4a8;
            --green: #4ade80;
            --serif: 'DM Serif Display', serif;
            --sans: 'Inter', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .top-bar {
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            background: rgba(10,10,10,0.8);
            backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-family: var(--serif);
            font-size: 24px;
            color: var(--peach);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand i { color: var(--accent); font-size: 18px; }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-menu .name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text2);
        }

        .btn-logout {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-logout:hover {
            border-color: var(--text2);
            background: rgba(255,255,255,0.05);
        }

        .main-content {
            flex: 1;
            padding: 48px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* Create SOS Form */
        .sos-hero{text-align:center;margin-bottom:48px}
        .sos-hero h1{font-family:var(--serif);font-size:42px;font-weight:400;letter-spacing:2px;color:var(--accent);margin-bottom:12px}
        .sos-hero p{font-size:15px;color:var(--text2);max-width:480px;margin:0 auto;line-height:1.7}
        .sos-button-wrap{display:flex;justify-content:center;margin:48px 0}
        .sos-button{
            width:220px;height:220px;border-radius:50%;
            background:radial-gradient(circle at 40% 35%,#ff8a70,#e8735a 40%,#c45040 80%);
            border:none;cursor:pointer;position:relative;
            display:flex;align-items:center;justify-content:center;flex-direction:column;
            box-shadow:0 0 60px rgba(232,115,90,.2),0 0 120px rgba(232,115,90,.08);
            transition:all .3s;font-family:var(--sans);
        }
        .sos-button:hover{transform:scale(1.03);box-shadow:0 0 80px rgba(232,115,90,.35),0 0 160px rgba(232,115,90,.12)}
        .sos-button::before{
            content:'';position:absolute;inset:-20px;border-radius:50%;
            border:1px solid rgba(232,115,90,.15);
            animation:sosPulse 3s ease-in-out infinite;
        }
        .sos-button::after{
            content:'';position:absolute;inset:-40px;border-radius:50%;
            border:1px solid rgba(232,115,90,.08);
            animation:sosPulse 3s ease-in-out infinite .5s;
        }
        @keyframes sosPulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.05);opacity:.5}}
        .sos-button .icon{font-size:28px;color:rgba(10,10,10,.6);margin-bottom:4px}
        .sos-button .label{font-size:24px;font-weight:800;color:#0a0a0a;letter-spacing:2px}
        
        .sos-bottom{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:900px;margin:0 auto}
        .sos-panel{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:28px}
        .radio-option{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid var(--border);border-radius:10px;margin-bottom:8px;cursor:pointer;transition:all .2s}
        .radio-option:hover{border-color:var(--accent)}
        .radio-option.selected{border-color:var(--accent);background:rgba(232,115,90,.05)}
        .radio-circle{width:18px;height:18px;border-radius:50%;border:2px solid var(--border);display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .radio-circle.active{border-color:var(--accent)}
        .radio-circle.active::after{content:'';width:8px;height:8px;border-radius:50%;background:var(--accent)}
        .radio-icon{width:32px;height:32px;border-radius:8px;background:var(--border);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--accent)}
        .radio-label{font-size:14px;font-weight:500;color:var(--text)}

        .gps-badge{position:absolute;top:12px;right:12px;padding:4px 12px;background:var(--card);border:1px solid var(--border);border-radius:6px;font-size:11px;font-weight:600;color:var(--text2);z-index:1000}
        .location-info{margin-top:16px}
        .location-info h3{font-size:16px;font-weight:600;color:var(--text);margin-bottom:4px}
        .location-info .coords{font-family:monospace;font-size:13px;color:var(--text2)}
        
        /* Tracker Card */
        .tracker-card {
            background: var(--card);
            border: 1px solid var(--accent);
            border-radius: 16px;
            padding: 40px;
            max-width: 800px;
            margin: 0 auto 60px;
            box-shadow: 0 12px 48px rgba(232,115,90,0.1);
        }
        
        .tracker-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
        }

        .tracker-header h2 {
            font-family: var(--serif);
            font-size: 28px;
            margin-bottom: 8px;
            color: var(--accent);
        }
        
        .tracker-header p {
            color: var(--text2);
            font-size: 14px;
        }
        
        .tracker-id {
            background: rgba(232,115,90,0.1);
            color: var(--accent);
            padding: 6px 12px;
            border-radius: 6px;
            font-family: monospace;
            font-weight: bold;
            font-size: 14px;
        }

        /* Pipeline */
        .pipeline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            margin-bottom: 48px;
        }
        
        .pipeline::before {
            content: '';
            position: absolute;
            top: 16px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--border);
            z-index: 1;
        }
        
        .pipeline-step {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            width: 80px;
        }
        
        .step-dot {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--card);
            border: 2px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: var(--text2);
            transition: all 0.3s;
        }
        
        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text2);
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
        }
        
        .pipeline-step.active .step-dot,
        .pipeline-step.completed .step-dot {
            border-color: var(--accent);
            color: var(--accent);
        }
        
        .pipeline-step.active .step-dot {
            background: rgba(232,115,90,0.1);
            box-shadow: 0 0 15px rgba(232,115,90,0.3);
        }

        .pipeline-step.completed .step-dot {
            background: var(--accent);
            color: var(--bg);
        }
        
        .pipeline-step.active .step-label,
        .pipeline-step.completed .step-label {
            color: var(--text);
        }

        .assigned-agency {
            background: rgba(10,10,10,0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }
        
        .agency-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--peach);
        }
        
        .agency-info h4 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        
        .agency-info p {
            color: var(--text2);
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-cancel {
            background: transparent;
            color: var(--text2);
            border: 1px solid var(--border);
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }
        .btn-cancel:hover {
            color: #ef4444;
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* History Table */
        .history-section {
            max-width: 900px;
            margin: 40px auto 0;
            border-top: 1px solid var(--border);
            padding-top: 40px;
        }

        .history-section h3 {
            font-family: var(--serif);
            font-size: 24px;
            margin-bottom: 24px;
            color: var(--text);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        
        th {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text2);
        }
        
        td {
            font-size: 14px;
            color: var(--text);
        }
        
        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .badge.resolved { background: rgba(74, 222, 128, 0.1); color: var(--green); }
        .badge.cancelled { background: rgba(255,255,255, 0.1); color: var(--text2); }

        .alert-success {
            background: rgba(74, 222, 128, 0.1);
            color: var(--green);
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            text-align: center;
            border: 1px solid rgba(74, 222, 128, 0.2);
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div class="brand">
            <i class="fas fa-shield-halved"></i>
            ResQNet Emergency Portal
        </div>
        <div class="user-menu">
            <span class="name">{{ auth()->user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-power-off"></i> Disconnect
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if($activeSos)
            <!-- Active SOS Tracker -->
            <div class="tracker-card">
                <div class="tracker-header">
                    <div>
                        <h2>Rescue operation active</h2>
                        <p>Location: {{ $activeSos->latitude }}, {{ $activeSos->longitude }}</p>
                        <p style="margin-top: 4px;">Reported: {{ $activeSos->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="tracker-id">ID: #{{ str_pad($activeSos->id, 5, '0', STR_PAD_LEFT) }}</div>
                </div>

                @php
                    $statuses = ['pending', 'assigned', 'dispatched', 'en_route', 'resolved'];
                    $currentIndex = array_search($activeSos->status, $statuses);
                    
                    $statusIcons = [
                        'pending' => 'fa-tower-broadcast',
                        'assigned' => 'fa-clipboard-check',
                        'dispatched' => 'fa-truck-fast',
                        'en_route' => 'fa-route',
                        'resolved' => 'fa-check-double'
                    ];
                    
                    $statusLabels = [
                        'pending' => 'Broadcasting',
                        'assigned' => 'Assigned',
                        'dispatched' => 'Dispatched',
                        'en_route' => 'En Route',
                        'resolved' => 'Resolved'
                    ];
                @endphp

                <div class="pipeline">
                    @foreach($statuses as $index => $status)
                        @php
                            $state = '';
                            if ($index < $currentIndex) $state = 'completed';
                            elseif ($index === $currentIndex) $state = 'active';
                        @endphp
                        <div class="pipeline-step {{ $state }}">
                            <div class="step-dot">
                                @if($state === 'completed')
                                    <i class="fas fa-check"></i>
                                @else
                                    <i class="fas {{ $statusIcons[$status] }}"></i>
                                @endif
                            </div>
                            <div class="step-label">{{ $statusLabels[$status] }}</div>
                        </div>
                    @endforeach
                </div>

                @if($activeSos->assignedAgency)
                    <div class="assigned-agency">
                        <div class="agency-icon"><i class="fas fa-building-shield"></i></div>
                        <div class="agency-info">
                            <h4>{{ $activeSos->assignedAgency->user->name }}</h4>
                            <p><i class="fas fa-phone"></i> {{ $activeSos->assignedAgency->contact_phone }}</p>
                        </div>
                    </div>
                @else
                    <div class="assigned-agency" style="justify-content: center; opacity: 0.7;">
                        <div class="agency-info" style="text-align: center;">
                            <p><i class="fas fa-spinner fa-spin"></i> Waiting for agency assignment...</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('sos.cancel', $activeSos->id) }}" onsubmit="return confirm('Are you sure you want to cancel this emergency request?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn-cancel">Cancel SOS Request</button>
                </form>
            </div>
        @else
            <!-- Create New SOS -->
            <form method="POST" action="{{ route('sos.store') }}" id="sosForm">
            @csrf
            <div class="sos-hero">
                <h1>EMERGENCY SOS</h1>
                <p>Transmit immediate distress signal. Rescue teams will be dispatched to your precise location.</p>
            </div>
            
            <div class="sos-button-wrap">
                <button type="submit" class="sos-button">
                    <div class="icon"><i class="fas fa-tower-broadcast"></i></div>
                    <div class="label">SEND</div>
                </button>
            </div>
            
            <div class="sos-bottom">
                <div class="sos-panel">
                    <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:16px">Nature of Emergency</div>
                    @php
                        $types = [
                            'medical' => ['icon' => 'fa-heart-pulse', 'label' => 'Medical Emergency'],
                            'fire' => ['icon' => 'fa-fire', 'label' => 'Fire / Structural'],
                            'flood_rescue' => ['icon' => 'fa-water', 'label' => 'Flood / Weather'],
                            'evacuation' => ['icon' => 'fa-person-running', 'label' => 'Evacuation'],
                            'food' => ['icon' => 'fa-utensils', 'label' => 'Food / Shelter'],
                            'other' => ['icon' => 'fa-triangle-exclamation', 'label' => 'Other Emergency'],
                        ];
                    @endphp
                    @foreach($types as $val => $t)
                    <label class="radio-option" onclick="selectType(this, '{{ $val }}')">
                        <div class="radio-circle {{ $loop->first ? 'active' : '' }}"></div>
                        <div class="radio-icon"><i class="fas {{ $t['icon'] }}"></i></div>
                        <span class="radio-label">{{ $t['label'] }}</span>
                    </label>
                    @endforeach
                    <input type="hidden" name="type" id="type-input" value="medical">
                    <input type="hidden" name="severity" value="critical">
                    <input type="hidden" name="victim_name" value="{{ auth()->user()->name }}">
                    <input type="hidden" name="victim_phone" value="{{ auth()->user()->phone }}">
                    <input type="hidden" name="victim_count" value="1">
                </div>
            
                <div class="sos-panel">
                    <div id="sos-map" style="height:250px;border-radius:10px;margin-bottom:16px;border:1px solid var(--border);z-index:1;position:relative;">
                        <div class="gps-badge">GPS Active</div>
                    </div>
                    <div class="location-info">
                        <div style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);margin-bottom:6px">Current Broadcast Location</div>
                        <h3>Your coordinates are being tracked</h3>
                        <div class="coords"><span id="lat-display">...</span>° N, <span id="lng-display">...</span>° E</div>
                    </div>
                    <input type="hidden" name="latitude" id="lat-input" value="">
                    <input type="hidden" name="longitude" id="lng-input" value="">
                </div>
            </div>
            </form>

            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
            function selectType(el, val) {
                document.querySelectorAll('.radio-option').forEach(o => { o.classList.remove('selected'); o.querySelector('.radio-circle').classList.remove('active'); });
                el.classList.add('selected');
                el.querySelector('.radio-circle').classList.add('active');
                document.getElementById('type-input').value = val;
            }
            
            // Default center Delhi
            let map = L.map('sos-map', { zoomControl: false }).setView([28.6139, 77.2090], 13);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
            }).addTo(map);
            
            const markerIcon = L.divIcon({
                className: 'custom-div-icon',
                html: '<div style="width:14px;height:14px;background:var(--accent);border-radius:50%;box-shadow:0 0 20px rgba(232,115,90,.5);border:2px solid #161616;"></div>',
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });
            
            let marker = L.marker([28.6139, 77.2090], {draggable: true, icon: markerIcon}).addTo(map);
            
            marker.on('dragend', function (e) {
                let pos = marker.getLatLng();
                updateLocation(pos.lat, pos.lng);
            });
            
            function updateLocation(lat, lng) {
                document.getElementById('lat-input').value = lat.toFixed(7);
                document.getElementById('lng-input').value = lng.toFixed(7);
                document.getElementById('lat-display').textContent = lat.toFixed(4);
                document.getElementById('lng-display').textContent = lng.toFixed(4);
            }
            
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(function(p){
                    let lat = p.coords.latitude;
                    let lng = p.coords.longitude;
                    updateLocation(lat, lng);
                    map.setView([lat, lng], 15);
                    marker.setLatLng([lat, lng]);
                }, function(error) {
                    console.warn('Geolocation failed:', error.message);
                    let badge = document.querySelector('.gps-badge');
                    if (badge) {
                        badge.textContent = 'GPS Unavailable (Drag Pin)';
                        badge.style.color = 'var(--accent, #e8735a)';
                    }
                    fetch('https://ipapi.co/json/')
                        .then(res => res.json())
                        .then(data => {
                            if(data.latitude && data.longitude) {
                                updateLocation(data.latitude, data.longitude);
                                map.setView([data.latitude, data.longitude], 14);
                                marker.setLatLng([data.latitude, data.longitude]);
                                if(badge) {
                                    badge.textContent = 'Approx. Location (Drag Pin)';
                                    badge.style.color = '#f4be37';
                                }
                            }
                        }).catch(e => console.log('IP fallback failed', e));
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            }
            document.querySelector('.radio-option').classList.add('selected');
            </script>
        @endif

        @if($history->count() > 0)
            <!-- History -->
            <div class="history-section">
                <h3>Past Emergency Requests</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $item)
                        <tr>
                            <td>{{ $item->created_at->format('M d, Y H:i') }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->type)) }}</td>
                            <td>{{ number_format($item->latitude, 4) }}, {{ number_format($item->longitude, 4) }}</td>
                            <td>
                                <span class="badge {{ $item->status }}">{{ $item->status }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</body>
</html>
