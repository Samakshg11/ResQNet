<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — ResQNet</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #0a0a0a;
            --card: #161616;
            --border: #1e1e1e;
            --text: #e8e0d8;
            --text2: #8a7f75;
            --muted: #5a534c;
            --accent: #e8735a;
            --peach: #f0c4a8;
            --serif: 'DM Serif Display', serif;
            --sans: 'Inter', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            -webkit-font-smoothing: antialiased;
            overflow: hidden;
        }

        .background-blob {
            position: fixed;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(232, 115, 90, 0.05) 0%, rgba(10, 10, 10, 0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(60px);
        }

        .blob-1 { top: -100px; right: -100px; }
        .blob-2 { bottom: -100px; left: -100px; }

        .auth-wrap {
            width: 100%;
            max-width: 440px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: rgba(22, 22, 22, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 56px 48px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }

        .brand {
            font-family: var(--serif);
            font-size: 26px;
            color: var(--peach);
            text-align: center;
            margin-bottom: 40px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand i { font-size: 20px; color: var(--accent); }

        .title {
            font-family: var(--serif);
            font-size: 32px;
            text-align: center;
            margin-bottom: 12px;
            font-weight: 400;
            color: var(--text);
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            color: var(--text2);
            margin-bottom: 44px;
            line-height: 1.5;
        }

        .form-group { margin-bottom: 24px; }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            font-size: 14px;
            color: var(--muted);
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: rgba(10, 10, 10, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 15px;
            font-family: inherit;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            background: rgba(10, 10, 10, 0.8);
            box-shadow: 0 0 0 4px rgba(232, 115, 90, 0.1);
        }

        .form-control:focus + i { color: var(--accent); }

        .form-error {
            color: var(--accent);
            font-size: 12px;
            margin-top: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remember-forgot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--text2);
            cursor: pointer;
            user-select: none;
        }

        .remember input {
            accent-color: var(--accent);
            width: 16px;
            height: 16px;
        }

        .forgot {
            font-size: 13px;
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(232, 115, 90, 0.3);
        }

        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 32px;
            font-size: 14px;
            color: var(--text2);
        }

        .auth-footer a {
            color: var(--accent);
            font-weight: 600;
            text-decoration: none;
            margin-left: 4px;
            transition: opacity 0.2s;
        }

        .auth-footer a:hover { opacity: 0.8; }

        @media (max-width: 480px) {
            .auth-card { padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <div class="background-blob blob-1"></div>
    <div class="background-blob blob-2"></div>

    <div class="auth-wrap">
        <div class="auth-card">
            <div class="brand">
                <i class="fas fa-shield-halved"></i>
                ResQNet
            </div>
            
            <h1 class="title">Welcome Back</h1>
            <p class="subtitle">Access the secure command center for coordinated emergency response.</p>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Command Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="officer@resqnet.org" required autofocus>
                    </div>
                    @error('email')
                        <div class="form-error">
                            <i class="fas fa-circle-exclamation"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Security Password</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="remember-forgot">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        <span>Remember credentials</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    Sign In <i class="fas fa-arrow-right"></i>
                </button>
            </form>
            
            <div class="auth-footer" style="display:flex; flex-direction:column; gap:8px;">
                <div>New to the network? <a href="{{ route('register') }}">Join as Citizen</a></div>
                <div style="font-size:13px; opacity:0.8; margin-top:4px;">Represent an organization? <a href="{{ route('register', ['tab' => 'agency']) }}">Register Your Agency</a></div>
            </div>
        </div>
    </div>
</body>
</html>

