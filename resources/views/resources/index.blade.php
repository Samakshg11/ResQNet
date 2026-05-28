@extends('layouts.app')
@section('title', 'Resources')
@section('content')
<div class="page-header"><div><h1>Resource</h1><p>Track, deploy, and manage operational resources.</p></div></div>
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
            <tr>
                <td style="color:var(--text);font-weight:500">{{ $r->name }}</td>
                <td style="text-transform:capitalize">{{ str_replace('_',' ',$r->category) }}</td>
                <td style="font-size:12px">{{ Str::limit($r->agency->name, 18) }}</td>
                <td style="font-weight:600;color:{{ $r->isLow() ? 'var(--accent)' : 'var(--green)' }}">{{ $r->available_quantity }} {{ $r->unit }}</td>
                <td>{{ $r->deployed_quantity }}</td>
                <td>{{ $r->total_quantity }}</td>
                <td><span class="badge badge-{{ $r->status === 'available' ? 'available' : ($r->status === 'depleted' ? 'critical' : 'pending') }}">{{ strtoupper($r->status) }}</span></td>
                <td>@if($r->status !== 'depleted' && $r->available_quantity > 0)<form method="POST" action="{{ route('resources.deploy', $r->id) }}" style="display:flex;gap:4px">@csrf<input type="number" name="quantity" class="form-control" style="width:60px;padding:6px 8px;font-size:12px" min="1" max="{{ $r->available_quantity }}" value="1"><button class="btn btn-ghost btn-sm">Deploy</button></form>@else<span style="color:var(--text-muted)">—</span>@endif</td>
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
