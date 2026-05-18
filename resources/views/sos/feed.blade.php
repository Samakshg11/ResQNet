@extends('layouts.app')
@section('title', 'SOS Live Feed')
@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <div>
        <h1>Live SOS Feed</h1>
        <p>Real-time incoming distress signals. Claim missions for your agency.</p>
    </div>
    <div style="display:flex; align-items:center; gap:12px; background:var(--card); padding:8px 16px; border:1px solid var(--border); border-radius:8px;">
        <div class="live-dot" style="width:8px; height:8px; background:var(--accent); border-radius:50%; animation:pulse 1.5s infinite;"></div>
        <span style="font-size:13px; font-weight:600; color:var(--text);">Live Updates Active</span>
    </div>
</div>

@if($sosRequests->count() > 0)
    <div class="table-wrap card" style="padding:0; overflow:hidden;">
        <table>
            <thead>
                <tr>
                    <th>SOS ID</th>
                    <th>Time</th>
                    <th>Type</th>
                    <th>Severity</th>
                    <th>Location</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sosRequests as $sos)
                <tr>
                    <td style="font-family:monospace; font-weight:600;">#{{ str_pad($sos->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $sos->created_at->diffForHumans() }}</td>
                    <td>{{ ucwords(str_replace('_', ' ', $sos->type)) }}</td>
                    <td><span class="badge badge-{{ $sos->severity }}">{{ $sos->severity }}</span></td>
                    <td>{{ number_format($sos->latitude, 4) }}, {{ number_format($sos->longitude, 4) }}</td>
                    <td>
                        <form method="POST" action="{{ route('agency.sos.assign', $sos->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-sm">Claim SOS</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $sosRequests->links() }}
@else
    <div class="card" style="text-align:center; padding:48px;">
        <i class="fas fa-satellite-dish" style="font-size:48px; color:var(--text-muted); margin-bottom:16px;"></i>
        <h3 style="font-size:18px; margin-bottom:8px;">No Pending SOS Requests</h3>
        <p style="color:var(--text-secondary); font-size:14px;">The network is clear. No active emergencies require attention.</p>
    </div>
@endif

<style>
@keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.3;} }
</style>
<script>
    // Refresh feed every 30 seconds
    setTimeout(() => {
        window.location.reload();
    }, 30000);
</script>
@endsection
