@extends('layouts.app')
@section('title', 'My Volunteers')
@section('content')

<div class="page-header">
    <div>
        <h1>Agency Volunteers</h1>
        <p>Manage your dedicated personnel and rescue volunteers.</p>
    </div>
</div>

<div class="grid-2">
    <div>
        <div class="card">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
                <span class="section-title" style="margin-bottom:0">Active Volunteers ({{ $volunteers->total() }})</span>
            </div>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Skills</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($volunteers as $v)
                        <tr>
                            <td style="color:var(--text);font-weight:500">{{ $v->user->name }}</td>
                            <td>
                                <div>{{ $v->user->email }}</div>
                                <div style="font-size:11px;color:var(--text-muted)">{{ $v->user->phone }}</div>
                            </td>
                            <td>
                                @foreach($v->skills ?? [] as $skill)
                                    <span class="badge" style="background:var(--border);color:var(--text-secondary);margin-bottom:4px;display:inline-block">{{ $skill }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge badge-{{ $v->availability === 'available' ? 'available' : 'pending' }}">
                                    {{ ucfirst(str_replace('_', ' ', $v->availability)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;color:var(--text-muted);padding:30px">
                                No volunteers added yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top:20px">
                {{ $volunteers->links() }}
            </div>
        </div>
    </div>
    
    <div>
        <div class="card" style="position:sticky;top:100px">
            <span class="section-title">Add New Volunteer</span>
            <p style="font-size:13px;color:var(--text-secondary);margin-bottom:24px">Register a new volunteer directly to your agency. They will use their email and the default password (<strong>password123</strong>) to log in to the Volunteer Hub.</p>
            
            <form method="POST" action="{{ route('volunteers.storeMy') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Rahul Sharma">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Specialized Skills (Comma separated)</label>
                    <input type="text" name="skills" class="form-control" placeholder="e.g. First Aid, Search & Rescue, Diver">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Home / Base Address</label>
                    <input type="text" name="address" class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:10px">
                    <i class="fas fa-user-plus"></i> Register Volunteer
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
