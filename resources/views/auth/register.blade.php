<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — ResQNet</title>
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
            max-width: 480px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: rgba(22, 22, 22, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }

        .brand {
            font-family: var(--serif);
            font-size: 24px;
            color: var(--peach);
            text-align: center;
            margin-bottom: 32px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .brand i { font-size: 18px; color: var(--accent); }

        .title {
            font-family: var(--serif);
            font-size: 28px;
            text-align: center;
            margin-bottom: 8px;
            font-weight: 400;
            color: var(--text);
        }

        .subtitle {
            text-align: center;
            font-size: 13px;
            color: var(--text2);
            margin-bottom: 32px;
            line-height: 1.5;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            font-size: 13px;
            color: var(--muted);
            transition: color 0.3s;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: rgba(10, 10, 10, 0.5);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-size: 14px;
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

        select.form-control { appearance: none; }

        .form-error {
            color: var(--accent);
            font-size: 11px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }

        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(232, 115, 90, 0.3);
        }

        .btn-submit:active { transform: translateY(0); }

        .auth-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 13px;
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
            .auth-card { padding: 32px 20px; }
            .form-row { grid-template-columns: 1fr; gap: 0; }
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
            
            <h1 class="title">Join Network</h1>
            <p class="subtitle">Enroll in the decentralized coordination system.</p>
            
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Full Name</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                    </div>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Command Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="officer@resqnet.org" required>
                    </div>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Security Password</label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Key</label>
                        <div class="input-wrapper">
                            <i class="fas fa-key"></i>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Sector Role</label>
                        <div class="input-wrapper">
                            <i class="fas fa-id-badge"></i>
                            <select name="role" class="form-control">
                                <option value="victim">Citizen</option>
                                <option value="volunteer">Volunteer</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Direct Contact</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone" class="form-control" placeholder="+91-XXXXXXXXXX">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    Initialize Account <i class="fas fa-plus"></i>
                </button>
            </form>
            
            <div class="auth-footer">
                Already part of the network? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>

