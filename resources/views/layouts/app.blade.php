<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ResQNet') — Disaster Rescue Coordination</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #0a0a0a;
            --bg-elevated: #111111;
            --bg-card: #161616;
            --bg-card-hover: #1c1c1c;
            --border: #1e1e1e;
            --border-light: #2a2a2a;
            --text: #e8e0d8;
            --text-secondary: #8a7f75;
            --text-muted: #5a534c;
            --accent: #e8735a;
            --accent-soft: rgba(232,115,90,.12);
            --accent-glow: rgba(232,115,90,.25);
            --peach: #f0c4a8;
            --peach-soft: rgba(240,196,168,.1);
            --green: #5cb85c;
            --green-soft: rgba(92,184,92,.12);
            --yellow: #c9a84c;
            --yellow-soft: rgba(201,168,76,.12);
            --blue: #5b8db8;
            --blue-soft: rgba(91,141,184,.12);
            --red-soft: rgba(232,115,90,.12);
            --serif: 'DM Serif Display', serif;
            --sans: 'Inter', -apple-system, sans-serif;
        }
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: var(--sans); background: var(--bg); color: var(--text); min-height: 100vh; -webkit-font-smoothing: antialiased; }
        a { color: inherit; text-decoration: none; }
        ::selection { background: var(--accent); color: #fff; }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed; top: 0; left: 0; width: 220px; height: 100vh;
            background: var(--bg); border-right: 1px solid var(--border);
            display: flex; flex-direction: column; z-index: 100;
        }
        .sidebar-header {
            padding: 28px 20px 24px; border-bottom: 1px solid var(--border);
        }
        .sidebar-header .brand {
            font-family: var(--serif); font-size: 16px; font-weight: 400;
            color: var(--peach); letter-spacing: 0.5px;
        }
        .sidebar-header h2 {
            font-family: var(--serif); font-size: 20px; font-weight: 400;
            color: var(--text); margin-top: 2px; line-height: 1.2;
        }
        .sidebar-header .subtitle {
            font-size: 11px; color: var(--accent); font-weight: 500;
            letter-spacing: 0.5px; margin-top: 4px;
        }
        .sidebar-dispatch {
            margin: 20px 16px 8px;
        }
        .btn-dispatch {
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 12px; background: var(--accent);
            border: none; border-radius: 8px; color: #0a0a0a;
            font-size: 13px; font-weight: 700; font-family: var(--sans);
            cursor: pointer; transition: all .2s; letter-spacing: 0.3px;
        }
        .btn-dispatch:hover { opacity: .9; box-shadow: 0 0 20px var(--accent-glow); }
        .sidebar-nav { flex: 1; padding: 12px 12px; overflow-y: auto; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 8px; color: var(--text-secondary);
            font-size: 13px; font-weight: 500; transition: all .2s;
            margin-bottom: 2px; border-left: 3px solid transparent;
        }
        .nav-item:hover { color: var(--text); background: var(--bg-card); }
        .nav-item.active {
            color: var(--peach); background: var(--peach-soft);
            border-left-color: var(--accent);
        }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; opacity: .8; }
        .nav-item .count {
            margin-left: auto; font-size: 11px; font-weight: 600;
            background: var(--accent); color: #0a0a0a; padding: 1px 7px;
            border-radius: 8px; min-width: 20px; text-align: center;
        }
        .sidebar-bottom {
            padding: 12px 12px; border-top: 1px solid var(--border);
        }
        .sidebar-bottom .nav-item { color: var(--text-muted); font-size: 12px; }
        .user-block {
            display: flex; align-items: center; gap: 10px;
            padding: 12px 14px; border-top: 1px solid var(--border); margin-top: 4px;
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: var(--border-light); display: flex;
            align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600; color: var(--text-secondary);
        }
        .user-info .name { font-size: 13px; font-weight: 600; color: var(--text); }
        .user-info .role { font-size: 10px; color: var(--text-muted); font-weight: 500; }

        /* ── Top Navigation Bar ── */
        .topnav {
            position: fixed; top: 0; left: 220px; right: 0; height: 56px;
            background: rgba(10,10,10,.85); backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px; z-index: 90;
        }
        .topnav-brand {
            font-family: var(--serif); font-size: 18px; color: var(--peach);
            letter-spacing: 0.5px;
        }
        .topnav-links { display: flex; align-items: center; gap: 28px; }
        .topnav-links a {
            font-size: 13px; font-weight: 500; color: var(--text-secondary);
            transition: color .2s; position: relative; padding: 4px 0;
        }
        .topnav-links a:hover, .topnav-links a.active { color: var(--text); }
        .topnav-links a.active::after {
            content: ''; position: absolute; bottom: -2px; left: 0; right: 0;
            height: 1px; background: var(--accent);
        }
        .topnav-right { display: flex; align-items: center; gap: 16px; }
        .topnav-icon {
            width: 36px; height: 36px; border-radius: 8px; display: flex;
            align-items: center; justify-content: center; color: var(--text-secondary);
            font-size: 15px; cursor: pointer; transition: all .2s;
        }
        .topnav-icon:hover { color: var(--text); background: var(--bg-card); }
        .btn-report {
            padding: 8px 18px; border: 1px solid var(--accent);
            border-radius: 6px; color: var(--accent); font-size: 12px;
            font-weight: 600; font-family: var(--sans); cursor: pointer;
            background: transparent; transition: all .2s; letter-spacing: 0.3px;
        }
        .btn-report:hover { background: var(--accent); color: #0a0a0a; }

        /* ── Main Content ── */
        .main { margin-left: 220px; padding-top: 56px; min-height: 100vh; }
        .content { padding: 32px; }
        .page-header {
            margin-bottom: 28px; display: flex; align-items: center;
            justify-content: space-between;
        }
        .page-header h1 {
            font-family: var(--serif); font-size: 28px; font-weight: 400;
            color: var(--text);
        }
        .page-header p { font-size: 14px; color: var(--text-secondary); margin-top: 4px; }

        /* ── Cards ── */
        .card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 12px; padding: 24px; transition: border-color .3s;
        }
        .card:hover { border-color: var(--border-light); }

        /* ── Stat Cards ── */
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .stat-card {
            background: var(--bg-card); border: 1px solid var(--border);
            border-radius: 12px; padding: 20px 22px; position: relative;
        }
        .stat-card-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px;
        }
        .stat-card-label {
            font-size: 10px; font-weight: 700; letter-spacing: 1.5px;
            text-transform: uppercase; color: var(--text-muted);
        }
        .stat-card-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: var(--border); color: var(--text-muted); font-size: 14px;
        }
        .stat-card-value {
            font-size: 36px; font-weight: 300; color: var(--text);
            font-family: var(--sans); letter-spacing: -1px;
        }
        .stat-card-value .unit {
            font-size: 14px; font-weight: 500; color: var(--text-secondary);
            margin-left: 4px; letter-spacing: 0;
        }
        .stat-card-sub {
            font-size: 12px; color: var(--text-muted); margin-top: 6px;
        }
        .stat-sparkline {
            height: 3px; background: var(--border); border-radius: 2px;
            margin-top: 14px; overflow: hidden;
        }
        .stat-sparkline-fill {
            height: 100%; border-radius: 2px; background: var(--accent);
        }
        .stat-card .live-dot {
            width: 6px; height: 6px; border-radius: 50%; background: var(--accent);
            display: inline-block; margin-right: 4px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.3} }

        /* ── Alert Banner ── */
        .alert-banner {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; border-radius: 10px; margin-bottom: 24px;
            border: 1px solid rgba(232,115,90,.2);
            background: linear-gradient(90deg, rgba(232,115,90,.06), transparent);
        }
        .alert-banner-left { display: flex; align-items: center; gap: 12px; }
        .alert-banner .live-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--accent); animation: pulse 1.5s infinite; }
        .alert-banner-text { font-size: 14px; font-weight: 600; color: var(--text); }
        .alert-banner-action {
            font-size: 13px; color: var(--text-secondary); font-weight: 500;
            cursor: pointer; transition: color .2s;
        }
        .alert-banner-action:hover { color: var(--text); }

        /* ── Tables ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left; padding: 12px 16px; font-size: 10px;
            font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--text-muted); border-bottom: 1px solid var(--border);
        }
        td { padding: 14px 16px; font-size: 13px; border-bottom: 1px solid var(--border); color: var(--text-secondary); }
        tr:hover td { background: var(--bg-card-hover); }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 4px; font-size: 10px;
            font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase;
        }
        .badge-critical, .badge-extreme { background: var(--red-soft); color: var(--accent); border: 1px solid rgba(232,115,90,.2); }
        .badge-high { background: var(--yellow-soft); color: var(--yellow); border: 1px solid rgba(201,168,76,.2); }
        .badge-medium { background: var(--blue-soft); color: var(--blue); border: 1px solid rgba(91,141,184,.2); }
        .badge-low, .badge-verified, .badge-resolved, .badge-available { background: var(--green-soft); color: var(--green); border: 1px solid rgba(92,184,92,.2); }
        .badge-pending { background: var(--yellow-soft); color: var(--yellow); border: 1px solid rgba(201,168,76,.2); }
        .badge-active { background: var(--red-soft); color: var(--accent); border: 1px solid rgba(232,115,90,.2); }
        .badge-assigned, .badge-dispatched, .badge-en_route { background: var(--blue-soft); color: var(--blue); border: 1px solid rgba(91,141,184,.2); }
        .badge-contained, .badge-monitoring { background: var(--yellow-soft); color: var(--yellow); border: 1px solid rgba(201,168,76,.2); }
        .badge-suspended, .badge-rejected, .badge-cancelled { background: rgba(90,83,76,.15); color: var(--text-muted); border: 1px solid var(--border); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 10px 20px; border-radius: 8px; font-size: 13px;
            font-weight: 600; border: none; cursor: pointer;
            transition: all .2s; font-family: var(--sans);
        }
        .btn-primary { background: var(--accent); color: #0a0a0a; }
        .btn-primary:hover { opacity: .9; box-shadow: 0 0 20px var(--accent-glow); }
        .btn-outline {
            background: transparent; color: var(--text);
            border: 1px solid var(--border-light);
        }
        .btn-outline:hover { border-color: var(--text-muted); }
        .btn-ghost { background: var(--bg-card); color: var(--text-secondary); border: 1px solid var(--border); }
        .btn-ghost:hover { color: var(--text); border-color: var(--border-light); }
        .btn-sm { padding: 7px 14px; font-size: 12px; border-radius: 6px; }
        .btn-success { background: var(--green); color: #0a0a0a; }

        /* ── Forms ── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1.2px;
            color: var(--text-muted); margin-bottom: 8px;
        }
        .form-control {
            width: 100%; padding: 11px 14px; background: var(--bg);
            border: 1px solid var(--border); border-radius: 8px;
            color: var(--text); font-size: 14px; font-family: var(--sans);
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: var(--accent); }
        select.form-control { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%235a534c' d='M6 8L1 3h10z'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; }
        textarea.form-control { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-error { color: var(--accent); font-size: 12px; margin-top: 4px; }

        /* ── Layout helpers ── */
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
        .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
        .section-title {
            font-family: var(--serif); font-size: 20px; font-weight: 400;
            color: var(--text); margin-bottom: 20px;
        }
        .section-title-sm {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 1.5px; color: var(--text-muted); margin-bottom: 16px;
        }

        /* ── Flash messages ── */
        .flash { padding: 14px 20px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; }
        .flash-success { background: var(--green-soft); border: 1px solid rgba(92,184,92,.2); color: var(--green); }
        .flash-error { background: var(--red-soft); border: 1px solid rgba(232,115,90,.2); color: var(--accent); }

        /* ── Pagination ── */
        .pagination { display: flex; align-items: center; gap: 4px; margin-top: 24px; justify-content: center; }
        .pagination a, .pagination span { padding: 8px 14px; border-radius: 6px; font-size: 12px; font-weight: 500; }
        .pagination a { background: var(--bg-card); border: 1px solid var(--border); color: var(--text-secondary); }
        .pagination a:hover { border-color: var(--accent); color: var(--accent); }
        .pagination .active span { background: var(--accent); color: #0a0a0a; }

        /* ── Footer ── */
        .footer {
            padding: 40px 32px; border-top: 1px solid var(--border);
            margin-top: 60px; display: flex; justify-content: space-between;
            align-items: flex-start;
        }
        .footer-brand { font-family: var(--serif); font-size: 18px; color: var(--text); margin-bottom: 8px; }
        .footer-copy { font-size: 12px; color: var(--text-muted); line-height: 1.6; max-width: 250px; }
        .footer-links { display: flex; gap: 28px; }
        .footer-links a { font-size: 12px; color: var(--text-muted); transition: color .2s; }
        .footer-links a:hover { color: var(--text); }

        /* ── Responsive ── */
        @media(max-width:768px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .topnav { left: 0; }
            .stat-grid { grid-template-columns: 1fr 1fr; }
            .grid-2, .grid-3, .form-row { grid-template-columns: 1fr; }
        }

        /* ── Animations ── */
        @keyframes fadeUp { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .fade-up { animation: fadeUp .5s ease; }
    </style>
    @yield('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    @auth
    {{-- Sidebar --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="brand">ResQNet</div>
            <h2>ResQNet<br>Dashboard</h2>
            <div class="subtitle">Regional Command Unit</div>
        </div>
        <div class="sidebar-dispatch">
            <a href="{{ route('sos.create') }}" class="btn-dispatch">
                <i class="fas fa-asterisk"></i> Emergency Dispatch
            </a>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-th-large"></i> Command Center
            </a>
            <a href="{{ route('agencies.index') }}" class="nav-item {{ request()->routeIs('agencies.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Agency Management
            </a>
            <a href="{{ route('disasters.index') }}" class="nav-item {{ request()->routeIs('disasters.*') ? 'active' : '' }}">
                <i class="fas fa-map-marked-alt"></i> Disaster Map
            </a>
            <a href="{{ route('sos.index') }}" class="nav-item {{ request()->routeIs('sos.*') ? 'active' : '' }}">
                <i class="fas fa-asterisk"></i> SOS Feed
                @php $pc = \App\Models\SOSRequest::where('status','pending')->count(); @endphp
                @if($pc > 0)<span class="count">{{ $pc }}</span>@endif
            </a>
            <a href="{{ route('analytics.index') }}" class="nav-item {{ request()->routeIs('analytics.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Analytics
            </a>
        </nav>
        <div class="sidebar-bottom">
            <a href="{{ route('resources.index') }}" class="nav-item {{ request()->routeIs('resources.*') ? 'active' : '' }}">
                <i class="fas fa-boxes-stacked"></i> Resources
            </a>
            <a href="{{ route('volunteers.index') }}" class="nav-item {{ request()->routeIs('volunteers.*') ? 'active' : '' }}">
                <i class="fas fa-user-group"></i> Volunteers
            </a>
            <a href="{{ route('alerts.index') }}" class="nav-item {{ request()->routeIs('alerts.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Alerts
            </a>
        </div>
        <div class="user-block" style="border-top: 1px solid var(--border); padding-top: 16px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('profile.edit') }}" style="text-decoration: none; display: flex; align-items: center; gap: 10px;">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 1) }}</div>
                <div class="user-info">
                    <div class="name">{{ Str::limit(auth()->user()->name, 12) }}</div>
                    <div class="role">ID: RQN-{{ str_pad(auth()->user()->id, 3, '0', STR_PAD_LEFT) }}</div>
                </div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topnav-icon" style="background:transparent; border:none; width: 32px; height: 32px;" title="Logout">
                    <i class="fas fa-right-from-bracket" style="font-size: 14px; color: var(--accent);"></i>
                </button>
            </form>
        </div>
    </aside>

    {{-- Top Nav --}}
    <nav class="topnav">
        <div class="topnav-brand">ResQNet</div>
        <div class="topnav-links">
            <a href="{{ route('agencies.index') }}" class="{{ request()->routeIs('agencies.*') ? 'active' : '' }}">Agency</a>
            <a href="{{ route('disasters.index') }}" class="{{ request()->routeIs('disasters.*') ? 'active' : '' }}">Disasters</a>
            <a href="{{ route('sos.index') }}" class="{{ request()->routeIs('sos.*') ? 'active' : '' }}">SOS</a>
            <a href="{{ route('analytics.index') }}" class="{{ request()->routeIs('analytics.*') ? 'active' : '' }}">Analytics</a>
        </div>
        <div class="topnav-right">
            <div class="topnav-icon"><i class="fas fa-bell"></i></div>
            <a href="{{ route('sos.create') }}" class="btn-report">Report Emergency</a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                @csrf
                <button type="submit" class="topnav-icon" style="background:transparent; border:none;" title="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </nav>

    {{-- Main --}}
    <main class="main">
        <div class="content fade-up">
            @if(session('success'))
                <div class="flash flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="flash flash-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}</div>
            @endif
            @yield('content')
        </div>
        <footer class="footer">
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
    </main>
    @else
        @yield('content')
    @endauth
    @yield('scripts')
</body>
</html>
