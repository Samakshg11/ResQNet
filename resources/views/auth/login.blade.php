<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ResQNet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:'Inter',sans-serif;background:#0a0a0a;color:#e8e0d8;min-height:100vh;display:flex;align-items:center;justify-content:center;-webkit-font-smoothing:antialiased}
        .auth-wrap{width:100%;max-width:420px;padding:20px}
        .auth-card{background:#161616;border:1px solid #1e1e1e;border-radius:16px;padding:48px 40px}
        .brand{font-family:'DM Serif Display',serif;font-size:22px;color:#f0c4a8;text-align:center;margin-bottom:40px;letter-spacing:.5px}
        .title{font-family:'DM Serif Display',serif;font-size:28px;text-align:center;margin-bottom:8px;font-weight:400}
        .subtitle{text-align:center;font-size:13px;color:#8a7f75;margin-bottom:36px}
        .form-group{margin-bottom:20px}
        .form-label{display:block;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:#5a534c;margin-bottom:8px}
        .form-control{width:100%;padding:12px 14px;background:#0a0a0a;border:1px solid #1e1e1e;border-radius:8px;color:#e8e0d8;font-size:14px;font-family:inherit;transition:border .2s}
        .form-control:focus{outline:none;border-color:#e8735a}
        .form-error{color:#e8735a;font-size:12px;margin-top:4px}
        .remember{display:flex;align-items:center;gap:8px;font-size:12px;color:#8a7f75;margin-bottom:16px;cursor:pointer}
        .btn-submit{width:100%;padding:13px;background:#e8735a;color:#0a0a0a;border:none;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;transition:all .2s}
        .btn-submit:hover{opacity:.9;box-shadow:0 0 20px rgba(232,115,90,.25)}
        .auth-footer{text-align:center;margin-top:24px;font-size:13px;color:#8a7f75}
        .auth-footer a{color:#e8735a;font-weight:600}
    </style>
</head>
<body>
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="brand">ResQNet</div>
            <h1 class="title">Welcome Back</h1>
            <p class="subtitle">Sign in to the command center</p>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@resqnet.org" required autofocus>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <label class="remember"><input type="checkbox" name="remember"> Remember me</label>
                <button type="submit" class="btn-submit">Sign In</button>
            </form>
            <div class="auth-footer">Don't have an account? <a href="{{ route('register') }}">Register</a></div>
        </div>
    </div>
</body>
</html>
