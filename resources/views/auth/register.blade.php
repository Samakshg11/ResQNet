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
            overflow-x: hidden;
            padding: 40px 0;
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
            max-width: 560px;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: rgba(22, 22, 22, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.4);
        }

        .brand {
            font-family: var(--serif);
            font-size: 24px;
            color: var(--peach);
            text-align: center;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .brand i { font-size: 18px; color: var(--accent); }

        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            background: rgba(10,10,10,0.5);
            padding: 6px;
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        .tab-btn {
            flex: 1;
            padding: 10px;
            text-align: center;
            background: transparent;
            border: none;
            color: var(--text2);
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .tab-btn.active {
            background: var(--card);
            color: var(--text);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            border: 1px solid var(--border);
        }

        .form-section { display: none; }
        .form-section.active { display: block; }

        .form-group { margin-bottom: 16px; }
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
            margin-top: 16px;
        }
        .btn-submit:hover {
            opacity: 0.95;
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(232, 115, 90, 0.3);
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
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
            
            <div class="tabs">
                <button type="button" class="tab-btn active" onclick="switchTab('victim')">Report Emergency / Citizen</button>
                <button type="button" class="tab-btn" onclick="switchTab('agency')">Register Your Agency</button>
            </div>
            
            <div id="form-victim" class="form-section active">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="intent" value="victim">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>
                        @if(old('intent') !== 'agency') @error('name')<div class="form-error">{{ $message }}</div>@enderror @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        @if(old('intent') !== 'agency') @error('email')<div class="form-error">{{ $message }}</div>@enderror @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <div class="input-wrapper">
                            <i class="fas fa-phone"></i>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        </div>
                        @if(old('intent') !== 'agency') @error('phone')<div class="form-error">{{ $message }}</div>@enderror @endif
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            @if(old('intent') !== 'agency') @error('password')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-key"></i>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Register & Report <i class="fas fa-arrow-right"></i></button>
                </form>
            </div>

            <div id="form-agency" class="form-section">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <input type="hidden" name="intent" value="agency">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Admin Full Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>
                            @if(old('intent') === 'agency') @error('name')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Admin Email</label>
                            <div class="input-wrapper">
                                <i class="fas fa-envelope"></i>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                            </div>
                            @if(old('intent') === 'agency') @error('email')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-lock"></i>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            @if(old('intent') === 'agency') @error('password')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm Password</label>
                            <div class="input-wrapper">
                                <i class="fas fa-key"></i>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid var(--border); margin: 20px 0;"></div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Agency Name</label>
                            <div class="input-wrapper">
                                <i class="fas fa-building"></i>
                                <input type="text" name="agency_name" class="form-control" value="{{ old('agency_name') }}" required>
                            </div>
                            @if(old('intent') === 'agency') @error('agency_name')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                        <div class="form-group">
                            <label class="form-label">Registration No.</label>
                            <div class="input-wrapper">
                                <i class="fas fa-id-card"></i>
                                <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number') }}" required>
                            </div>
                            @if(old('intent') === 'agency') @error('registration_number')<div class="form-error">{{ $message }}</div>@enderror @endif
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Agency Type</label>
                            <div class="input-wrapper">
                                <i class="fas fa-tag"></i>
                                <select name="agency_type" class="form-control" required>
                                    <option value="medical">Medical</option>
                                    <option value="fire_rescue">Fire Rescue</option>
                                    <option value="flood_rescue">Flood Rescue</option>
                                    <option value="food_supply">Food Supply</option>
                                    <option value="police">Police</option>
                                    <option value="ngo">NGO</option>
                                    <option value="ambulance">Ambulance</option>
                                    <option value="civil_defense">Civil Defense</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Total Teams</label>
                            <div class="input-wrapper">
                                <i class="fas fa-users"></i>
                                <input type="number" name="total_teams" class="form-control" value="{{ old('total_teams') }}" min="1" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Contact Phone</label>
                            <div class="input-wrapper">
                                <i class="fas fa-phone-alt"></i>
                                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Region & State</label>
                            <div class="input-wrapper">
                                <i class="fas fa-map-marker-alt"></i>
                                <input type="text" name="region" class="form-control" placeholder="Region" style="width:50%; border-right:none; border-top-right-radius:0; border-bottom-right-radius:0;" value="{{ old('region') }}" required>
                                <input type="text" name="state" class="form-control" placeholder="State" style="width:50%; border-top-left-radius:0; border-bottom-left-radius:0;" value="{{ old('state') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <div class="input-wrapper">
                            <i class="fas fa-map"></i>
                            <input type="text" name="address" class="form-control" value="{{ old('address') }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Submit Agency Application <i class="fas fa-check"></i></button>
                </form>
            </div>
            
            <div class="auth-footer">
                Already part of the network? <a href="{{ route('login') }}">Sign In</a>
            </div>
        </div>
    </div>
    
    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.form-section').forEach(sec => sec.classList.remove('active'));
            
            if(tab === 'victim') {
                document.querySelectorAll('.tab-btn')[0].classList.add('active');
                document.getElementById('form-victim').classList.add('active');
            } else {
                document.querySelectorAll('.tab-btn')[1].classList.add('active');
                document.getElementById('form-agency').classList.add('active');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab') || urlParams.get('intent');
            
            if (tabParam === 'agency' || tabParam === 'agency_admin') {
                switchTab('agency');
            } else if (tabParam === 'victim') {
                switchTab('victim');
            } else {
                @if(old('intent') === 'agency')
                    switchTab('agency');
                @else
                    switchTab('victim');
                @endif
            }
        });
    </script>
</body>
</html>

