@extends('layouts.app')
@section('title', 'Resources')
@section('content')
<div class="page-header"><div><h1>Resource Inventory</h1><p>Track, deploy, and manage operational resources.</p></div></div>
<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card"><span class="stat-label">Total Resources</span><span class="stat-value">{{ number_format($summary['total']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Available</span><span class="stat-value">{{ number_format($summary['available']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Depleted</span><span class="stat-value">{{ number_format($summary['depleted']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Shortages</span><span class="stat-value">{{ number_format($summary['shortages']) }}</span></div>
</div>
<form method="GET" action="{{ route('resources.index') }}" class="card" style="margin-bottom:24px">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;align-items:end">
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Category</label>
            <select name="category" class="form-control">
                <option value="">All categories</option>
                @foreach(\App\Models\Resource::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group" style="margin-bottom:0">
            <label class="form-label">Status</label>
            <select name="status" class="form-control">
                <option value="">All statuses</option>
                @foreach(\App\Models\Resource::STATUSES as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div style="display:flex;gap:8px;align-items:center">
            <button class="btn btn-primary" type="submit">Apply Filters</button>
            <a href="{{ route('resources.index') }}" class="btn btn-ghost">Reset</a>
        </div>
    </div>
</form>
@if($shortages->count() > 0)
<div class="alert-banner" style="margin-bottom:24px">
    <div class="alert-banner-left"><span class="live-dot"></span><span class="alert-banner-text">{{ $shortages->count() }} resource(s) below minimum threshold</span></div>
</div>
@endif
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Resource</th><th>Category</th><th>Agency</th><th>Available</th><th>Deployed</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($resources as $r)
            @php
                $statusClass = $r->status === 'available' ? 'available' : ($r->status === 'depleted' ? 'critical' : 'pending');
            @endphp
            <tr>
                <td style="color:var(--text);font-weight:500">{{ $r->name }}</td>
                <td>{{ \App\Models\Resource::CATEGORIES[$r->category] ?? Str::headline($r->category) }}</td>
                <td style="font-size:12px">{{ Str::limit($r->agency?->name ?? 'Unassigned', 18) }}</td>
                <td style="font-weight:600;color:{{ $r->isLow() ? 'var(--accent)' : 'var(--green)' }}">{{ $r->available_quantity }} {{ $r->unit }}</td>
                <td>{{ $r->deployed_quantity }}</td>
                <td>{{ $r->total_quantity }}</td>
                <td><span class="badge badge-{{ $statusClass }}">{{ strtoupper($r->status) }}</span></td>
                <td>
                    @if($r->status !== 'depleted' && $r->available_quantity > 0)
                        <form method="POST" action="{{ route('resources.deploy', $r->id) }}" style="display:flex;gap:6px;align-items:center">
                            @csrf
                            <input
                                type="number"
                                name="quantity"
                                class="form-control"
                                style="width:72px;padding:6px 8px;font-size:12px"
                                min="1"
                                max="{{ $r->available_quantity }}"
                                value="1"
                                aria-label="Quantity to deploy for {{ $r->name }}"
                            >
                            <button class="btn btn-ghost btn-sm" type="submit">Deploy</button>
                        </form>
                    @else
                        <span style="color:var(--text-muted)">—</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px">No resources.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $resources->links() }}</div>
</div>
@endsection
