@extends('layouts.app')
@section('title', 'Broadcast Alert')
@section('content')
<div class="page-header"><h1>Send Broadcast</h1></div>
<div class="card" style="max-width:680px">
    <form method="POST" action="{{ route('alerts.store') }}">@csrf
        <div class="form-group"><label class="form-label">Alert Title</label><input type="text" name="title" class="form-control" placeholder="CRITICAL: Cyclone Landfall Imminent" required></div>
        <div class="form-group"><label class="form-label">Message</label><textarea name="message" class="form-control" placeholder="Detailed alert message..." required></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">Type</label><select name="type" class="form-control" required><option value="emergency">Emergency</option><option value="warning">Warning</option><option value="advisory">Advisory</option><option value="info">Info</option></select></div>
            <div class="form-group"><label class="form-label">Scope</label><select name="scope" class="form-control" required><option value="all">All Agencies</option><option value="agency_type">By Type</option><option value="region">By Region</option></select></div>
        </div>
        <div class="form-group"><label class="form-label">Related Disaster</label><select name="disaster_id" class="form-control"><option value="">None</option>@foreach($disasters as $d)<option value="{{ $d->id }}">{{ $d->title }}</option>@endforeach</select></div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center"><i class="fas fa-bullhorn"></i> Send Broadcast</button>
    </form>
</div>
@endsection
