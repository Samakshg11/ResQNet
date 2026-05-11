@extends('layouts.app')
@section('title', 'Emergency SOS')
@section('styles')
<style>
    .sos-hero{text-align:center;margin-bottom:48px}
    .sos-hero h1{font-family:var(--serif,serif);font-size:42px;font-weight:400;letter-spacing:2px;color:var(--accent,#e8735a);margin-bottom:12px}
    .sos-hero p{font-size:15px;color:var(--text-secondary,#8a7f75);max-width:480px;margin:0 auto;line-height:1.7}
    .sos-button-wrap{display:flex;justify-content:center;margin:48px 0}
    .sos-button{
        width:220px;height:220px;border-radius:50%;
        background:radial-gradient(circle at 40% 35%,#ff8a70,#e8735a 40%,#c45040 80%);
        border:none;cursor:pointer;position:relative;
        display:flex;align-items:center;justify-content:center;flex-direction:column;
        box-shadow:0 0 60px rgba(232,115,90,.2),0 0 120px rgba(232,115,90,.08);
        transition:all .3s;font-family:var(--sans,sans-serif);
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
    .sos-button .sublabel{font-size:12px;font-weight:700;color:rgba(10,10,10,.5);letter-spacing:1px}
    .sos-bottom{display:grid;grid-template-columns:1fr 1fr;gap:24px;max-width:900px;margin:0 auto}
    .sos-panel{background:var(--bg-card,#161616);border:1px solid var(--border,#1e1e1e);border-radius:12px;padding:28px}
    .radio-option{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid var(--border,#1e1e1e);border-radius:10px;margin-bottom:8px;cursor:pointer;transition:all .2s}
    .radio-option:hover{border-color:var(--accent,#e8735a)}
    .radio-option.selected{border-color:var(--accent,#e8735a);background:rgba(232,115,90,.05)}
    .radio-circle{width:18px;height:18px;border-radius:50%;border:2px solid var(--border-light,#2a2a2a);display:flex;align-items:center;justify-content:center;flex-shrink:0}
    .radio-circle.active{border-color:var(--accent,#e8735a)}
    .radio-circle.active::after{content:'';width:8px;height:8px;border-radius:50%;background:var(--accent,#e8735a)}
    .radio-icon{width:32px;height:32px;border-radius:8px;background:var(--border,#1e1e1e);display:flex;align-items:center;justify-content:center;font-size:14px;color:var(--accent,#e8735a)}
    .radio-label{font-size:14px;font-weight:500;color:var(--text,#e8e0d8)}
    .map-placeholder{background:var(--bg,#0a0a0a);border:1px solid var(--border,#1e1e1e);border-radius:10px;height:200px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;margin-bottom:16px}
    .map-placeholder::before{content:'';position:absolute;inset:0;background:repeating-linear-gradient(0deg,transparent,transparent 20px,rgba(232,115,90,.03) 20px,rgba(232,115,90,.03) 21px),repeating-linear-gradient(90deg,transparent,transparent 20px,rgba(232,115,90,.03) 20px,rgba(232,115,90,.03) 21px)}
    .gps-badge{position:absolute;top:12px;right:12px;padding:4px 12px;background:var(--bg-card,#161616);border:1px solid var(--border,#1e1e1e);border-radius:6px;font-size:11px;font-weight:600;color:var(--text-secondary,#8a7f75)}
    .map-dot{width:12px;height:12px;border-radius:50%;background:var(--accent,#e8735a);box-shadow:0 0 20px rgba(232,115,90,.5);z-index:2}
    .location-info{margin-bottom:20px}
    .location-info .section-title-sm{margin-bottom:6px}
    .location-info h3{font-size:16px;font-weight:600;color:var(--text,#e8e0d8);margin-bottom:4px}
    .location-info .coords{font-family:monospace;font-size:13px;color:var(--text-secondary,#8a7f75)}
    .eta-card{display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--bg,#0a0a0a);border:1px solid var(--border,#1e1e1e);border-radius:10px}
    .eta-icon{width:36px;height:36px;border-radius:8px;background:rgba(232,115,90,.1);display:flex;align-items:center;justify-content:center;color:var(--accent,#e8735a);font-size:16px}
    .eta-text .label{font-size:12px;color:var(--text-secondary,#8a7f75)}
    .eta-text .value{font-size:14px;font-weight:700;color:var(--accent,#e8735a)}
</style>
@endsection
@section('content')
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
        <div class="sublabel">SOS</div>
    </button>
</div>

<div class="sos-bottom">
    <div class="sos-panel">
        <div class="section-title-sm" style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted,#5a534c);margin-bottom:16px">Nature of Emergency</div>
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
        <div class="map-placeholder">
            <div class="gps-badge">GPS Active</div>
            <div class="map-dot"></div>
        </div>
        <div class="location-info">
            <div class="section-title-sm" style="font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--text-muted,#5a534c);margin-bottom:6px">Current Broadcast Location</div>
            <h3>Sector 4, Urban District Zone</h3>
            <div class="coords"><span id="lat-display">28.6139</span>° N, <span id="lng-display">77.2090</span>° E</div>
        </div>
        <input type="hidden" name="latitude" id="lat-input" value="28.6139">
        <input type="hidden" name="longitude" id="lng-input" value="77.2090">
        <div class="eta-card">
            <div class="eta-icon"><i class="fas fa-truck-medical"></i></div>
            <div class="eta-text">
                <div class="label">Nearby Rescue Teams</div>
                <div class="value">Est. Response: 4 Mins</div>
            </div>
        </div>
    </div>
</div>
</form>

<script>
function selectType(el, val) {
    document.querySelectorAll('.radio-option').forEach(o => { o.classList.remove('selected'); o.querySelector('.radio-circle').classList.remove('active'); });
    el.classList.add('selected');
    el.querySelector('.radio-circle').classList.add('active');
    document.getElementById('type-input').value = val;
}
if(navigator.geolocation){
    navigator.geolocation.getCurrentPosition(function(p){
        document.getElementById('lat-input').value=p.coords.latitude.toFixed(7);
        document.getElementById('lng-input').value=p.coords.longitude.toFixed(7);
        document.getElementById('lat-display').textContent=p.coords.latitude.toFixed(4);
        document.getElementById('lng-display').textContent=p.coords.longitude.toFixed(4);
    });
}
document.querySelector('.radio-option').classList.add('selected');
</script>
@endsection
