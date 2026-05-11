@extends('layouts.app')
@section('title', 'Register Agency')
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
        <div class="form-group"><label class="form-label">Total Teams</label><input type="number" name="total_teams" class="form-control" min="0"></div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Register Agency</button>
    </form>
</div>
@endsection
