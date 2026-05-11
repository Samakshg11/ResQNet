<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — ResQNet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:'Inter',sans-serif;background:#0a0a0a;color:#e8e0d8;min-height:100vh;display:flex;align-items:center;justify-content:center;-webkit-font-smoothing:antialiased}
        .auth-wrap{width:100%;max-width:440px;padding:20px}
        .auth-card{background:#161616;border:1px solid #1e1e1e;border-radius:16px;padding:44px 36px}
        .brand{font-family:'DM Serif Display',serif;font-size:22px;color:#f0c4a8;text-align:center;margin-bottom:32px}
        .title{font-family:'DM Serif Display',serif;font-size:26px;text-align:center;margin-bottom:8px;font-weight:400}
        .subtitle{text-align:center;font-size:13px;color:#8a7f75;margin-bottom:32px}
        .form-group{margin-bottom:16px}
        .form-label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#5a534c;margin-bottom:6px}
        .form-control{width:100%;padding:11px 14px;background:#0a0a0a;border:1px solid #1e1e1e;border-radius:8px;color:#e8e0d8;font-size:14px;font-family:inherit;transition:border .2s}
        .form-control:focus{outline:none;border-color:#e8735a}
        select.form-control{appearance:none}
        .form-error{color:#e8735a;font-size:12px;margin-top:4px}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .btn-submit{width:100%;padding:13px;background:#e8735a;color:#0a0a0a;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s;margin-top:8px}
        .btn-submit:hover{opacity:.9;box-shadow:0 0 20px rgba(232,115,90,.25)}
        .auth-footer{text-align:center;margin-top:24px;font-size:13px;color:#8a7f75}
        .auth-footer a{color:#e8735a;font-weight:600}
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="brand">ResQNet</div>
            <h1 class="title">Join ResQNet</h1>
            <p class="subtitle">Register to the coordination network</p>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="{{ old('name') }}" required>@error('name')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div class="form-group"><label class="form-label">Email Address</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required>@error('email')<div class="form-error">{{ $message }}</div>@enderror</div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required>@error('password')<div class="form-error">{{ $message }}</div>@enderror</div>
                    <div class="form-group"><label class="form-label">Confirm</label><input type="password" name="password_confirmation" class="form-control" required></div>
                </div>
                <div class="form-row">
                    <div class="form-group"><label class="form-label">Role</label><select name="role" class="form-control"><option value="victim">Citizen</option><option value="volunteer">Volunteer</option></select></div>
                    <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" placeholder="+91-XXXXXXXXXX"></div>
                </div>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>
            <div class="auth-footer">Already registered? <a href="{{ route('login') }}">Sign In</a></div>
        </div>
    </div>
</body>
</html>
