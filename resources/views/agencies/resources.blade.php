@extends('layouts.app')
@section('title', 'Agency Resources')
@section('content')

<div class="page-header">
    <div>
        <h1>Resource Inventory</h1>
        <p>Manage your agency's operational resources and equipment.</p>
    </div>
</div>

<div class="stats-grid" style="margin-bottom:24px">
    <div class="stat-card"><span class="stat-label">Total Resources</span><span class="stat-value">{{ number_format($summary['total']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Available</span><span class="stat-value">{{ number_format($summary['available']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Depleted</span><span class="stat-value">{{ number_format($summary['depleted']) }}</span></div>
    <div class="stat-card"><span class="stat-label">Shortages</span><span class="stat-value">{{ number_format($summary['shortages']) }}</span></div>
</div>

@if($shortages->count() > 0)
<div class="alert-banner" style="margin-bottom:24px">
    <div class="alert-banner-left"><span class="live-dot"></span><span class="alert-banner-text">{{ $shortages->count() }} resource(s) below minimum threshold</span></div>
</div>
@endif

<div class="grid-2">
    <div>
        <div class="card">
            <span class="section-title">Current Inventory</span>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Resource</th><th>Category</th><th>Available</th><th>Deployed</th><th>Utilized</th><th>Status</th><th>Action</th></tr></thead>
                    <tbody>
                    @forelse($resources as $r)
                    @php
                        $statusClass = $r->status === 'available' ? 'available' : ($r->status === 'depleted' ? 'critical' : 'pending');
                    @endphp
                    <tr>
                        <td style="color:var(--text);font-weight:500">{{ $r->name }}</td>
                        <td>{{ $r->categoryLabel() }}</td>
                        <td style="font-weight:600;color:{{ $r->isLow() ? 'var(--accent)' : 'var(--green)' }}">{{ $r->available_quantity }} {{ $r->unit }}</td>
                        <td>{{ $r->deployed_quantity }}</td>
                        <td>{{ $r->deployedPercentage() }}%</td>
                        <td><span class="badge badge-{{ $statusClass }}">{{ $r->statusLabel() }}</span></td>
                        <td>
                            @if($r->status !== 'depleted' && $r->available_quantity > 0)
                                <form method="POST" action="{{ route('agency.resources.deploy', $r->id) }}" style="display:flex;gap:6px;align-items:center">
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
                    <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No resources.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pagination">{{ $resources->links() }}</div>
        </div>
    </div>
    <div>
        <div class="card" style="position:sticky;top:100px">
            <span class="section-title">Add New Resource</span>
            <form method="POST" action="{{ route('agency.resources.store') }}">
                @csrf
                <input type="hidden" name="agency_id" value="{{ auth()->user()->agency->id ?? '' }}">
                <div class="form-group">
                    <label class="form-label">Resource Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Type IV Ambulance">
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        @foreach(\App\Models\Resource::CATEGORIES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Total Quantity</label>
                        <input type="number" name="total_quantity" class="form-control" min="1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Available Now</label>
                        <input type="number" name="available_quantity" class="form-control" min="0" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Unit</label>
                        <input type="text" name="unit" class="form-control" placeholder="e.g. units, kg, lit" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Min Threshold</label>
                        <input type="number" name="minimum_threshold" class="form-control" min="0" value="0">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px">
                    <i class="fas fa-plus"></i> Add Resource
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
