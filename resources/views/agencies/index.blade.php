@extends('layouts.app')
@section('title', 'Agency Directory')
@section('content')
<div class="page-header">
    <div>
        <h1>Agency Directory</h1>
    </div>
    <div style="display:flex;align-items:center;gap:12px">
        <a href="{{ route('agencies.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Register</a>
    </div>
</div>

{{-- Filters --}}
<div style="display:flex;align-items:center;gap:10px;margin-bottom:28px;flex-wrap:wrap">
    <select class="form-control" style="width:auto;padding:8px 32px 8px 14px;font-size:12px;border-radius:20px">
        <option>All Regions</option>
        @foreach($agencies->pluck('region')->unique() as $r)<option>{{ $r }}</option>@endforeach
    </select>
    <select class="form-control" style="width:auto;padding:8px 32px 8px 14px;font-size:12px;border-radius:20px">
        <option>All Types</option>
        @foreach(['medical','fire_rescue','flood_rescue','food_supply','police','ngo','ambulance','civil_defense'] as $t)
        <option value="{{ $t }}">{{ ucfirst(str_replace('_',' ',$t)) }}</option>
        @endforeach
    </select>
    <select class="form-control" style="width:auto;padding:8px 32px 8px 14px;font-size:12px;border-radius:20px">
        <option>Status</option>
        <option>Verified</option><option>Pending</option>
    </select>
</div>

{{-- Agency Cards Grid --}}
<div class="grid-3">
    @forelse($agencies as $a)
    <div class="card" style="padding:0;overflow:hidden">
        <div style="padding:24px 24px 20px">
            {{-- Header --}}
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
                <div style="width:44px;height:44px;border-radius:12px;background:{{ $a->status === 'verified' ? 'rgba(232,115,90,.12)' : 'var(--border)' }};display:flex;align-items:center;justify-content:center;font-size:18px;color:{{ $a->status === 'verified' ? 'var(--accent)' : 'var(--text-muted)' }}">
                    <i class="fas {{ $a->type === 'medical' ? 'fa-plus' : ($a->type === 'fire_rescue' ? 'fa-fire' : ($a->type === 'flood_rescue' ? 'fa-water' : ($a->type === 'police' ? 'fa-shield' : ($a->type === 'ambulance' ? 'fa-truck-medical' : 'fa-building')))) }}"></i>
                </div>
                <span class="badge badge-{{ $a->status }}" style="font-size:9px">
                    @if($a->status === 'verified')<span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--green);margin-right:4px"></span>@endif
                    {{ ucfirst($a->status) }}
                </span>
            </div>
            {{-- Name --}}
            <h3 style="font-size:17px;font-weight:600;color:var(--text);margin-bottom:4px">{{ Str::limit($a->name, 24) }}</h3>
            <p style="font-size:12px;color:var(--text-secondary)">{{ $a->region }} {{ $a->getTypeLabel() }}</p>

            {{-- Stats --}}
            <div style="display:flex;gap:8px;margin-top:20px">
                <div style="flex:1;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px">
                    <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--accent);margin-bottom:4px">Active Missions</div>
                    <div style="font-size:22px;font-weight:300;color:var(--text)">{{ $a->sosRequests->whereIn('status', ['assigned','dispatched','en_route'])->count() }}</div>
                </div>
                <div style="flex:1;background:var(--bg);border:1px solid var(--border);border-radius:8px;padding:12px 14px">
                    <div style="font-size:9px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--text-muted);margin-bottom:4px">Personnel</div>
                    <div style="font-size:22px;font-weight:300;color:var(--text)">{{ $a->total_volunteers ?: $a->total_teams }} <span style="font-size:12px;color:var(--text-secondary)">{{ $a->status === 'verified' ? 'Online' : '—' }}</span></div>
                </div>
            </div>
        </div>
        {{-- Footer --}}
        <a href="{{ route('agencies.show', $a->id) }}" style="display:block;text-align:center;padding:14px;border-top:1px solid var(--border);font-size:13px;font-weight:500;color:var(--text-secondary);transition:all .2s;background:var(--bg)">
            {{ $a->status === 'pending' ? 'Review Application' : 'View Details' }}
        </a>
    </div>
    @empty
    <p style="color:var(--text-muted);grid-column:1/-1;text-align:center;padding:40px">No agencies registered.</p>
    @endforelse
</div>
<div class="pagination" style="margin-top:32px">{{ $agencies->links() }}</div>
@endsection
