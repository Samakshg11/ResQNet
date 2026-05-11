@extends('layouts.app')
@section('title', 'Volunteers')
@section('content')
<div class="page-header"><div><h1>Volunteer Network</h1><p>Active field responders and specialists.</p></div></div>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>Name</th><th>Agency</th><th>Skills</th><th>Availability</th><th>Missions</th><th>Rating</th><th></th></tr></thead>
            <tbody>
            @forelse($volunteers as $v)
            <tr>
                <td><span style="color:var(--text);font-weight:500">{{ $v->user->name }}</span><br><span style="font-size:11px;color:var(--text-muted)">{{ $v->user->email }}</span></td>
                <td style="font-size:12px">{{ Str::limit($v->agency->name ?? 'Unassigned', 18) }}</td>
                <td><div style="display:flex;flex-wrap:wrap;gap:4px">@foreach(($v->skills ?? []) as $s)<span style="background:var(--accent-soft);color:var(--accent);padding:2px 8px;border-radius:4px;font-size:10px;font-weight:600">{{ $s }}</span>@endforeach</div></td>
                <td><span class="badge badge-{{ $v->availability === 'available' ? 'available' : ($v->availability === 'on_task' ? 'active' : 'pending') }}">{{ strtoupper(str_replace('_',' ',$v->availability)) }}</span></td>
                <td style="font-weight:500">{{ $v->total_missions }}</td>
                <td><span style="color:var(--yellow)">★</span> {{ number_format($v->rating, 1) }}</td>
                <td><a href="{{ route('volunteers.show', $v->id) }}" class="btn btn-ghost btn-sm">View</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center;color:var(--text-muted);padding:40px">No volunteers.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination">{{ $volunteers->links() }}</div>
</div>
@endsection
