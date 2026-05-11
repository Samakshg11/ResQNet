@extends('layouts.app')
@section('title', 'Report Disaster')
@section('content')
<div class="page-header"><h1>Report Disaster</h1></div>
<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('disasters.store') }}">@csrf
        <div class="form-group"><label class="form-label">Title</label><input type="text" name="title" class="form-control" placeholder="e.g. Cyclone Biparjoy — Gujarat Coast" required></div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" placeholder="Situation details..." required></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-control" required>@foreach(['flood','earthquake','cyclone','fire','landslide','tsunami','drought','industrial','other'] as $t)<option value="{{ $t }}">{{ ucfirst($t) }}</option>@endforeach</select></div>
            <div class="form-group"><label class="form-label">Severity</label><select name="severity" class="form-control" required>@foreach(['low','medium','high','critical'] as $s)<option value="{{ $s }}">{{ ucfirst($s) }}</option>@endforeach</select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Epicenter Lat</label><input type="number" name="epicenter_lat" class="form-control" step="0.0000001"></div>
            <div class="form-group"><label class="form-label">Epicenter Lng</label><input type="number" name="epicenter_lng" class="form-control" step="0.0000001"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Radius (km)</label><input type="number" name="radius_km" class="form-control" step="0.01"></div>
            <div class="form-group"><label class="form-label">Estimated Affected</label><input type="number" name="estimated_affected" class="form-control" min="0"></div>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Report Disaster</button>
    </form>
</div>
@endsection
