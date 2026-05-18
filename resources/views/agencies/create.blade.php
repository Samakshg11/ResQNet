@extends('layouts.app')
@section('title', 'Register Agency')
@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map { height: 250px; width: 100%; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border); z-index: 1; }
</style>
@endsection
@section('content')
<div class="page-header"><h1>Register Agency</h1></div>
<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('agencies.store') }}">@csrf
        <div class="form-group"><label class="form-label">Agency Name</label><input type="text" name="name" class="form-control" required></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Registration #</label><input type="text" name="registration_number" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-control" required>@foreach(['medical','fire_rescue','flood_rescue','food_supply','police','ngo','ambulance','civil_defense'] as $t)<option value="{{ $t }}">{{ ucfirst(str_replace('_',' ',$t)) }}</option>@endforeach</select></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Contact Email</label><input type="email" name="contact_email" class="form-control" required></div>
            <div class="form-group"><label class="form-label">Contact Phone</label><input type="text" name="contact_phone" class="form-control" required></div>
        </div>
        <div class="form-group"><label class="form-label">Address</label><input type="text" name="address" class="form-control" required></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Region</label><input type="text" name="region" class="form-control" required></div>
            <div class="form-group"><label class="form-label">State</label><input type="text" name="state" class="form-control" required></div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Agency Location (Pin on Map)</label>
            <div id="map"></div>
            <input type="hidden" name="latitude" id="lat-input" value="22.5">
            <input type="hidden" name="longitude" id="lng-input" value="79.0">
        </div>
        
        <div class="form-group"><label class="form-label">Total Teams</label><input type="number" name="total_teams" class="form-control" min="0"></div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Register Agency</button>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map', { zoomControl: false }).setView([28.6139, 77.2090], 13);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
        }).addTo(map);

        const markerIcon = L.divIcon({
            className: 'custom-div-icon',
            html: '<div style="width:14px;height:14px;background:var(--accent);border-radius:50%;box-shadow:0 0 15px var(--accent);border:2px solid #161616;"></div>',
            iconSize: [14, 14],
            iconAnchor: [7, 7]
        });

        let marker = L.marker([28.6139, 77.2090], { draggable: true, icon: markerIcon }).addTo(map);

        function updateInputs(lat, lng) {
            document.getElementById('lat-input').value = lat.toFixed(7);
            document.getElementById('lng-input').value = lng.toFixed(7);
        }

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function(e) {
            updateInputs(e.target.getLatLng().lat, e.target.getLatLng().lng);
        });
        
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                map.setView([lat, lng], 13);
                marker.setLatLng([lat, lng]);
                updateInputs(lat, lng);
            }, function(error) {
                console.warn("Geolocation failed or blocked:", error.message);
                fetch('https://ipapi.co/json/')
                    .then(res => res.json())
                    .then(data => {
                        if(data.latitude && data.longitude) {
                            map.setView([data.latitude, data.longitude], 14);
                            marker.setLatLng([data.latitude, data.longitude]);
                            updateInputs(data.latitude, data.longitude);
                        }
                    }).catch(e => console.log('IP fallback failed', e));
            }, {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            });
        }
    });
</script>
@endsection
