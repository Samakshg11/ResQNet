@extends('layouts.app')
@section('title', 'Profile Settings')
@section('content')
<div class="page-header">
    <div>
        <h1>Profile Settings</h1>
        <p>Manage your account settings and preferences.</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <span class="section-title">Personal Information</span>
        <form method="POST" action="{{ route('profile.update') }}" style="margin-top: 16px;">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled style="opacity:0.7">
                <small style="color:var(--text-muted);font-size:11px;margin-top:4px;display:block">Role cannot be changed. Contact admin for role updates.</small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </form>
    </div>
    
    <div>
        <div class="card" style="margin-bottom: 24px;">
            <span class="section-title">Security</span>
            <div style="margin-top:16px;">
                <p style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;">Ensure your account is using a long, random password to stay secure.</p>
                <button class="btn btn-ghost" onclick="alert('Password update flow to be implemented.')"><i class="fas fa-key"></i> Update Password</button>
            </div>
        </div>

        <div class="card" style="border-color: var(--red-soft);">
            <span class="section-title" style="color:var(--accent)">Danger Zone</span>
            <div style="margin-top:16px;">
                <p style="font-size:13px;color:var(--text-secondary);margin-bottom:16px;">Once you delete your account, all of its resources and data will be permanently deleted.</p>
                <button class="btn btn-outline" style="color:var(--accent);border-color:var(--accent)" onclick="alert('Account deletion requires confirmation.')"><i class="fas fa-trash"></i> Delete Account</button>
            </div>
        </div>
    </div>
</div>
@endsection
