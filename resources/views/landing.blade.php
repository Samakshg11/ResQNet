<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ResQNet — Precision Coordination for Critical Operations</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root{--bg:#0a0a0a;--card:#161616;--border:#1e1e1e;--text:#e8e0d8;--text2:#8a7f75;--muted:#5a534c;--accent:#e8735a;--peach:#f0c4a8;--serif:'DM Serif Display',serif;--sans:'Inter',sans-serif}
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:var(--sans);background:var(--bg);color:var(--text);-webkit-font-smoothing:antialiased}a{color:inherit;text-decoration:none}::selection{background:var(--accent);color:#fff}

        /* Navbar */
        .navbar{position:fixed;top:0;left:0;right:0;z-index:1000;padding:0 48px;height:60px;display:flex;align-items:center;justify-content:space-between;background:rgba(10,10,10,.9);backdrop-filter:blur(16px);border-bottom:1px solid var(--border)}
        .nav-brand{font-family:var(--serif);font-size:22px;color:var(--peach);letter-spacing:.5px}
        .nav-center{display:flex;gap:32px}
        .nav-center a{font-size:13px;font-weight:500;color:var(--text2);transition:color .2s}
        .nav-center a:hover{color:var(--text)}
        .nav-right{display:flex;align-items:center;gap:20px}
        .nav-icon{color:var(--text2);font-size:16px;cursor:pointer;transition:color .2s}
        .nav-icon:hover{color:var(--text)}
        .btn-report{padding:9px 20px;border:1px solid var(--accent);border-radius:6px;color:var(--accent);font-size:12px;font-weight:600;font-family:var(--sans);background:transparent;cursor:pointer;transition:all .2s;letter-spacing:.3px}
        .btn-report:hover{background:var(--accent);color:#0a0a0a}

        /* Hero */
        .hero{min-height:100vh;display:flex;flex-direction:column;justify-content:center;padding:140px 48px 80px;max-width:1200px;position:relative}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;padding:6px 16px;background:rgba(232,115,90,.08);border:1px solid rgba(232,115,90,.15);border-radius:20px;font-size:11px;font-weight:700;color:var(--accent);letter-spacing:1px;text-transform:uppercase;margin-bottom:32px;width:fit-content}
        .hero-badge .dot{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 1.5s infinite}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
        .hero h1{font-family:var(--serif);font-size:clamp(40px,5.5vw,64px);font-weight:400;line-height:1.15;color:var(--text);margin-bottom:28px;max-width:650px}
        .hero h1 .em{color:var(--peach)}
        .hero p{font-size:16px;color:var(--text2);line-height:1.8;max-width:560px;margin-bottom:44px}
        .hero-buttons{display:flex;gap:12px;flex-wrap:wrap}
        .btn-hero{padding:14px 28px;border-radius:8px;font-size:14px;font-weight:600;font-family:var(--sans);cursor:pointer;display:inline-flex;align-items:center;gap:10px;transition:all .3s;border:none}
        .btn-hero-primary{background:var(--accent);color:#0a0a0a}
        .btn-hero-primary:hover{box-shadow:0 0 30px rgba(232,115,90,.3);opacity:.9}
        .btn-hero-secondary{background:transparent;color:var(--text);border:1px solid var(--border)}
        .btn-hero-secondary:hover{border-color:var(--text2)}

        /* Stats Row */
        .stats-row{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;max-width:900px;margin-top:80px}
        .stat-box{background:var(--card);border:1px solid var(--border);border-radius:12px;padding:24px 28px}
        .stat-box-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px}
        .stat-box-label{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted)}
        .stat-box-label .dot{display:inline-block;width:5px;height:5px;border-radius:50%;background:var(--accent);margin-right:6px;animation:pulse 1.5s infinite}
        .stat-box-icon{width:28px;height:28px;border-radius:6px;background:var(--border);display:flex;align-items:center;justify-content:center;color:var(--muted);font-size:12px}
        .stat-box-value{font-size:40px;font-weight:300;color:var(--text);letter-spacing:-1px;margin-bottom:6px}
        .stat-box-sub{font-size:12px;color:var(--text2)}
        .stat-box-bar{height:3px;background:var(--border);border-radius:2px;margin-top:16px;overflow:hidden}
        .stat-box-bar-fill{height:100%;background:var(--accent);border-radius:2px}
        .stat-box-tags{display:flex;gap:6px;margin-top:10px}
        .stat-box-tags span{font-size:10px;color:var(--text2);background:var(--border);padding:3px 10px;border-radius:4px;font-weight:500}

        /* Footer */
        .footer-landing{padding:48px;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:flex-start;margin-top:120px}
        .footer-brand{font-family:var(--serif);font-size:20px;color:var(--text);margin-bottom:10px}
        .footer-copy{font-size:12px;color:var(--muted);line-height:1.6;max-width:260px}
        .footer-links{display:flex;gap:28px}
        .footer-links a{font-size:12px;color:var(--muted);transition:color .2s}
        .footer-links a:hover{color:var(--text)}

        @media(max-width:768px){
            .hero{padding:120px 24px 60px}
            .hero h1{font-size:36px}
            .stats-row{grid-template-columns:1fr}
            .nav-center{display:none}
            .footer-landing{flex-direction:column;gap:24px}
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/" class="nav-brand">ResQNet</a>
        <div class="nav-center">
            <a href="{{ route('agencies.index') }}">Agency</a>
            <a href="{{ route('disasters.index') }}">Disasters</a>
            <a href="{{ route('sos.index') }}">SOS</a>
            <a href="{{ route('analytics.index') }}">Analytics</a>
        </div>
        <div class="nav-right">
            <span class="nav-icon"><i class="fas fa-search"></i></span>
            <span class="nav-icon"><i class="fas fa-bell"></i></span>
            @auth
                <a href="{{ route('sos.create') }}" class="btn-report">Report Emergency</a>
            @else
                <a href="{{ route('login') }}" class="btn-report">Report Emergency</a>
            @endauth
        </div>
    </nav>

    <section class="hero">
        <div class="hero-badge"><span class="dot"></span> Active Coordination Network</div>
        <h1>Precision Coordination for <span class="em">Critical Operations</span>.</h1>
        <p>The centralized intelligence hub for disaster response. Real-time mapping, resource allocation, and multi-agency coordination deployed securely on sovereign infrastructure.</p>
        <div class="hero-buttons">
            @auth
                <a href="{{ route('sos.create') }}" class="btn-hero btn-hero-primary"><i class="fas fa-asterisk"></i> Report Emergency</a>
                <a href="{{ route('dashboard') }}" class="btn-hero btn-hero-secondary"><i class="fas fa-map-marked-alt"></i> View Live Map</a>
            @else
                <a href="{{ route('register') }}" class="btn-hero btn-hero-primary"><i class="fas fa-asterisk"></i> Report Emergency</a>
                <a href="{{ route('login') }}" class="btn-hero btn-hero-secondary"><i class="fas fa-map-marked-alt"></i> View Live Map</a>
            @endauth
        </div>

        <div class="stats-row">
            <div class="stat-box">
                <div class="stat-box-header">
                    <span class="stat-box-label">Lives Saved</span>
                    <div class="stat-box-icon"><i class="fas fa-shield-halved"></i></div>
                </div>
                <div class="stat-box-value">{{ number_format(\App\Models\Disaster::sum('rescued_count')) }}</div>
                <div class="stat-box-bar"><div class="stat-box-bar-fill" style="width:72%"></div></div>
            </div>
            <div class="stat-box">
                <div class="stat-box-header">
                    <span class="stat-box-label"><span class="dot"></span>Active Missions</span>
                    <div class="stat-box-icon"><i class="fas fa-triangle-exclamation"></i></div>
                </div>
                <div class="stat-box-value">{{ \App\Models\SOSRequest::whereNotIn('status',['resolved','cancelled'])->count() }}</div>
                <div class="stat-box-sub">Across {{ \App\Models\Agency::where('status','verified')->count() }} regional sectors</div>
            </div>
            <div class="stat-box">
                <div class="stat-box-header">
                    <span class="stat-box-label">Active Resources</span>
                    <div class="stat-box-icon"><i class="fas fa-clipboard-list"></i></div>
                </div>
                <div class="stat-box-value">{{ number_format(\App\Models\Resource::sum('available_quantity')) }}</div>
                <div class="stat-box-tags">
                    <span>Vehicles</span><span>Personnel</span><span>Medical</span>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer-landing">
        <div>
            <div class="footer-brand">ResQNet</div>
            <div class="footer-copy">&copy; {{ date('Y') }} ResQNet. Official Emergency Response Coordination Platform.</div>
        </div>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Government Seals</a>
            <a href="#">Emergency Hotlines</a>
        </div>
    </footer>
</body>
</html>
