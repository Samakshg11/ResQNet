@extends('layouts.app')
@section('title', 'SOS Feed')
@section('content')
<div class="page-header">
    <div><h1>SOS Feed</h1><p>Real-time emergency distress signals across all sectors.</p></div>

</div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>ID</th><th>Victim</th><th>Type</th><th>Severity</th><th>Status</th><th>Victims</th><th>Assigned Unit</th><th>Time</th><th></th></tr></thead>
            <tbody>
            @forelse($sosRequests as $sos)
            <tr>
                <td style="font-family:monospace;font-size:11px;color:var(--text-muted)">{{ substr($sos->id, 0, 8) }}</td>
                <td><span style="color:var(--text);font-weight:500">{{ $sos->victim_name ?? 'Anonymous' }}</span><br><span style="font-size:11px;color:var(--text-muted)">{{ $sos->victim_phone ?? '' }}</span></td>
                <td>{{ str_replace('_', ' ', ucfirst($sos->type)) }}</td>
                <td><span class="badge badge-{{ $sos->severity }}">{{ strtoupper($sos->severity) }}</span></td>
                <td><span class="badge badge-{{ $sos->status }}">{{ strtoupper(str_replace('_', ' ', $sos->status)) }}</span></td>
                <td>{{ $sos->victim_count }}</td>
                <td style="font-size:12px">{{ Str::limit($sos->assignedAgency->name ?? '—', 20) }}</td>
                <td style="font-size:11px;color:var(--text-muted)">{{ $sos->created_at->diffForHumans() }}</td>
                <td><a href="{{ route('sos.show', $sos->id) }}" class="btn btn-ghost btn-sm">View</a></td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;color:var(--text-muted);padding:40px">No SOS requests.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $sosRequests->links() }}</div>
</div>
@endsection
