<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>ResQNet | Sovereign Shield Command</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <style>
        @keyframes pulse-red {
            0%, 100% { box-shadow: 0 0 0 0 rgba(229, 62, 62, 0.7); }
            50% { box-shadow: 0 0 30px 10px rgba(229, 62, 62, 0.4); }
        }
        @keyframes drift {
            from { transform: translate(0, 0); opacity: 0; }
            50% { opacity: 0.5; }
            to { transform: translate(100px, -100px); opacity: 0; }
        }
        @keyframes data-flow {
            0% { background-position: 0% 0%; }
            100% { background-position: 100% 100%; }
        }
        @keyframes ticker {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        @keyframes scan-vertical {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }

        .data-grid-bg {
            background-color: #0c0f0f;
            background-image: 
                linear-gradient(rgba(0, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
        }

        .tactical-glass {
            background: rgba(12, 15, 15, 0.6);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .signal-node {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #00FFFF;
            border-radius: 50%;
            filter: blur(1px);
            animation: drift 15s infinite linear;
        }

        .scan-bar {
            height: 2px;
            width: 100%;
            background: linear-gradient(90deg, transparent, #00FFFF, transparent);
            position: absolute;
            animation: scan-vertical 4s infinite linear;
        }

        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-stagger.active > * {
            opacity: 1;
            transform: translateY(0);
        }
        .reveal-stagger.active > *:nth-child(2) { transition-delay: 0.1s; }
        .reveal-stagger.active > *:nth-child(3) { transition-delay: 0.2s; }

        .map-mesh {
            mask-image: radial-gradient(circle at center, black 40%, transparent 80%);
            perspective: 1000px;
        }

        .nav-shrink {
            transition: all 0.4s ease;
        }
        .nav-shrink.scrolled {
            height: 64px;
            background: rgba(12, 15, 15, 0.95);
        }
    </style>
</head>
<body class="bg-obsidian text-on-background font-body overflow-x-hidden selection:bg-alert-cyan/30">
    <!-- Nav -->
    <nav class="fixed top-0 left-0 right-0 z-[100] h-24 flex items-center justify-between px-10 tactical-glass border-b border-white/5 nav-shrink" id="mainNav">
        <div class="flex items-center gap-8">
            <a href="/" class="flex items-center gap-3 text-decoration-none">
                <span class="font-headline text-2xl font-extrabold tracking-tighter text-white">RESQNET</span>
            </a>
            <div class="hidden lg:flex gap-8 text-[11px] font-bold uppercase tracking-widest text-white/50">
                <a class="hover:text-alert-cyan transition-colors" href="{{ route('agencies.index') }}">Strategic Assets</a>
                <a class="hover:text-alert-cyan transition-colors" href="{{ route('disasters.index') }}">Command Centers</a>
                <a class="hover:text-alert-cyan transition-colors" href="{{ route('analytics.index') }}">Satellite Uplink</a>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <div class="hidden md:flex flex-col items-end mr-4">
                <span class="text-[9px] font-bold text-alert-cyan uppercase tracking-widest">System Status</span>
                <span class="text-[10px] text-white/40">Sovereign Node: Active</span>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="bg-emergency-red/10 border border-emergency-red/40 text-emergency-red px-6 py-2 rounded-full text-xs font-bold hover:bg-emergency-red hover:text-white transition-all text-decoration-none text-center">
                    CONSOLE
                </a>
            @else
                <a href="{{ route('login') }}" class="bg-emergency-red/10 border border-emergency-red/40 text-emergency-red px-6 py-2 rounded-full text-xs font-bold hover:bg-emergency-red hover:text-white transition-all text-decoration-none text-center">
                    ENCRYPTED LOGIN
                </a>
            @endauth
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-stretch pt-24 overflow-hidden data-grid-bg">
        <!-- Floating Nodes Background -->
        <div class="absolute inset-0 pointer-events-none opacity-40">
            <div class="signal-node" style="top: 20%; left: 10%; animation-delay: 0s;"></div>
            <div class="signal-node" style="top: 50%; left: 30%; animation-delay: 2s;"></div>
            <div class="signal-node" style="top: 80%; left: 15%; animation-delay: 4s;"></div>
            <div class="signal-node" style="top: 40%; left: 60%; animation-delay: 1s;"></div>
            <div class="signal-node" style="top: 10%; left: 80%; animation-delay: 5s;"></div>
        </div>
        
        <!-- Left: Command Panel -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-12 lg:px-24 z-10 relative reveal-stagger active">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/5 border border-white/10 rounded-full mb-6 w-fit">
                <span class="w-1.5 h-1.5 rounded-full bg-alert-cyan animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-alert-cyan">Secure Terminal Link 0.94</span>
            </div>
            <h1 class="font-headline text-5xl lg:text-7xl font-extrabold text-white leading-tight mb-8">
                ResQNet:<br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-white to-white/40">The Sovereign Shield.</span>
            </h1>
            <p class="text-white/60 text-lg max-w-lg mb-12 leading-relaxed font-light">
                Nation-scale crisis management infrastructure. Real-time neural coordination for the next generation of public safety.
            </p>
            <div class="flex flex-wrap gap-6">
                <a href="{{ route('sos.my') }}" class="group relative px-10 py-5 bg-emergency-red rounded-xl font-bold text-white transition-all hover:scale-105 active:scale-95 overflow-hidden shadow-[0_0_50px_rgba(229,62,62,0.4)] text-decoration-none text-center flex items-center justify-center" style="animation: pulse-red 2s infinite">
                    <span class="relative z-10 flex items-center gap-3">
                        <span class="material-symbols-outlined">sos</span>
                        REPORT EMERGENCY
                    </span>
                </a>
                <a href="{{ route('analytics.index') }}" class="px-10 py-5 tactical-glass border-white/20 rounded-xl font-bold text-white hover:bg-white/10 transition-all border text-decoration-none text-center flex items-center justify-center">
                    MONITOR NETWORK
                </a>
            </div>
        </div>
        
        <!-- Right: Map Interface -->
        <div class="hidden lg:flex w-1/2 relative items-center justify-center p-12">
            <div class="relative w-full max-w-2xl aspect-[4/5] tactical-glass rounded-[40px] p-1 border-white/10 shadow-2xl overflow-hidden group">
                <div class="scan-bar"></div>
                <div class="absolute inset-0 map-mesh opacity-80 transition-transform duration-700 group-hover:scale-110">
                    <!-- 3D Map Visual Overlay -->
                    <div class="w-full h-full bg-gradient-to-b from-transparent via-alert-cyan/10 to-transparent flex items-center justify-center">
                        <div class="relative w-full flex items-center justify-center">
                            <img alt="India Map Overlay" class="w-[80%] mx-auto opacity-20 grayscale invert" src="https://lh3.googleusercontent.com/aida/ADBb0ug-6AqEgtVjvXHSOQJdkYcIXJy_CftiduH7r8vk82MKiKmDzrGIeCmt8jUWPkbIhJpf4PR0iSVI6lowKIO5hnujoX_UAEA6RBmJhhjS0CAs5_GGKIBG4_Tg7BKASh0k7PyW56BJifdFgBMaNlB8hNrNXTkrIrigURRoMRbT4FX-s1oooklOU2nO-Mk16TRrRLRvkY0G8dds0awMAId5z4kxjhXWvhH3Cb0_zOXOavYUifmT7vExzbgUT8A"/>
                            <div class="absolute top-1/4 left-1/2 w-4 h-4 bg-alert-cyan rounded-full animate-ping"></div>
                            <div class="absolute bottom-1/3 right-1/4 w-3 h-3 bg-emergency-red rounded-full animate-ping" style="animation-delay: 1s"></div>
                        </div>
                    </div>
                </div>
                <!-- Tactical Data Overlay -->
                <div class="absolute top-10 left-10 space-y-4">
                    <div class="tactical-glass p-4 rounded-xl border-white/5">
                        <div class="text-[9px] font-bold text-alert-cyan uppercase mb-1">Live Feed</div>
                        <div class="text-xl font-mono text-white">28.6N 77.2E</div>
                    </div>
                    <div class="tactical-glass p-4 rounded-xl border-white/5">
                        <div class="text-[9px] font-bold text-white/40 uppercase mb-1">Active Missions</div>
                        @php
                            $activeMissions = \App\Models\SOSRequest::where('status', 'pending')->count();
                            $totalMissions = max(12, \App\Models\SOSRequest::count());
                        @endphp
                        <div class="text-xl font-mono text-white">{{ str_pad($activeMissions, 2, '0', STR_PAD_LEFT) }}/{{ str_pad($totalMissions, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Real-Time Pulse Ticker -->
    <div class="bg-obsidian border-y border-white/5 py-4 overflow-hidden">
        <div class="flex gap-12 whitespace-nowrap animate-[ticker_30s_linear_infinite] hover:[animation-play-state:paused]">
            <div class="flex items-center gap-8 text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">
                <span class="text-alert-cyan flex items-center gap-2"><span class="material-symbols-outlined text-sm animate-spin">sync</span> SCANNING REGION: NCR...</span>
                <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> UNIT 42 DEPLOYED</span>
                <span class="text-emergency-red flex items-center gap-2"><span class="material-symbols-outlined text-sm">warning</span> FLOOD ALERT: KERALA</span>
                <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-alert-cyan rounded-full"></span> SATELLITE UPLINK STABLE</span>
                <span>// MISSION TRACE 0X9A4F</span>
            </div>
            <!-- Duplicated for seamless loop -->
            <div class="flex items-center gap-8 text-[10px] font-bold uppercase tracking-[0.2em] text-white/40">
                <span class="text-alert-cyan flex items-center gap-2"><span class="material-symbols-outlined text-sm animate-spin">sync</span> SCANNING REGION: NCR...</span>
                <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> UNIT 42 DEPLOYED</span>
                <span class="text-emergency-red flex items-center gap-2"><span class="material-symbols-outlined text-sm">warning</span> FLOOD ALERT: KERALA</span>
                <span class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-alert-cyan rounded-full"></span> SATELLITE UPLINK STABLE</span>
                <span>// MISSION TRACE 0X9A4F</span>
            </div>
        </div>
    </div>

    <!-- Agency Ecosystem -->
    <section class="py-32 px-12 lg:px-24 reveal-stagger">
        <div class="flex flex-col items-center text-center mb-20">
            <h2 class="font-headline text-4xl font-bold text-white mb-4">The Sovereign Ecosystem</h2>
            <p class="text-white/40 max-w-xl">A unified grid of India's elite first-response agencies, connected via ResQNet core.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="group relative tactical-glass p-8 rounded-3xl border-white/5 hover:border-alert-cyan/30 transition-all cursor-crosshair">
                <div class="h-20 flex items-center justify-center mb-6 grayscale group-hover:grayscale-0 transition-all">
                    <img alt="Agency" class="h-16 opacity-50 group-hover:opacity-100" src="https://lh3.googleusercontent.com/aida/ADBb0ug-6AqEgtVjvXHSOQJdkYcIXJy_CftiduH7r8vk82MKiKmDzrGIeCmt8jUWPkbIhJpf4PR0iSVI6lowKIO5hnujoX_UAEA6RBmJhhjS0CAs5_GGKIBG4_Tg7BKASh0k7PyW56BJifdFgBMaNlB8hNrNXTkrIrigURRoMRbT4FX-s1oooklOU2nO-Mk16TRrRLRvkY0G8dds0awMAId5z4kxjhXWvhH3Cb0_zOXOavYUifmT7vExzbgUT8A"/>
                </div>
                <div class="text-center">
                    <div class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-1">Response Agency</div>
                    <div class="text-sm font-bold text-white group-hover:text-alert-cyan">NDRF STRATEGIC</div>
                </div>
                <!-- Tactical Stats on Hover -->
                <div class="absolute inset-0 bg-obsidian/95 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-center rounded-3xl">
                    <div class="space-y-4 text-left">
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Personnel Online</div>
                            <div class="text-xl font-mono text-white">1,420</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Fleet Readiness</div>
                            <div class="text-xl font-mono text-white">98.4%</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="group relative tactical-glass p-8 rounded-3xl border-white/5 hover:border-alert-cyan/30 transition-all cursor-crosshair">
                <div class="h-20 flex items-center justify-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-white/10 flex items-center justify-center opacity-30">
                        <img class="w-8 h-8 filter invert opacity-60" src="https://lh3.googleusercontent.com/aida/ADBb0ug5RUmAALsrqPjYynEGUOnC2Z6b_P9KAsXpLqwq1eNpxo1tYx2HlCDUd_TECxf4p8v4Ymp62j8fOQzKXJ8IQ4sSbmSYQXjmbB-GDMd799EKFfVXYFUnlgyNz0hD2_MwcVsdhMBJ1SwWmb46ZWgDP0mbfZSOr5RtHZw4dNz4LBemaIN3jfBHOupQjexPHWk-itisZ8IDZNQXUdkM59EWlzgCOeIkLyNDARRKy4oDx_NWlP3jo560UUv2zIc"/>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-1">Urban Security</div>
                    <div class="text-sm font-bold text-white group-hover:text-alert-cyan">POLICE TASK FORCE</div>
                </div>
                <div class="absolute inset-0 bg-obsidian/95 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-center rounded-3xl">
                    <div class="space-y-4 text-left">
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Active Patrols</div>
                            <div class="text-xl font-mono text-white">842</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Response Time</div>
                            <div class="text-xl font-mono text-white">4.2m</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="group relative tactical-glass p-8 rounded-3xl border-white/5 hover:border-alert-cyan/30 transition-all cursor-crosshair">
                <div class="h-20 flex items-center justify-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-white/10 flex items-center justify-center opacity-30">
                        <img class="w-8 h-8 filter invert opacity-60" src="https://lh3.googleusercontent.com/aida/ADBb0uht1hChjQDGNrVIU0zotIb4hrgQcZQThzXegxbW7v7sW3XF8lQ66DbgRVUa9tmjLx0dQfiHeef7gWoXH0l1L9_qrSw6LwIdqc7H_FX55053soe1cNQX3w-7EPwVHUQylN01eqzjZtMxp6GXfrmhA-fmDg9P1SjjxcebgFbd60SEGRuY1EG3FI2lTj5BMYcbYyfD_ZjZHgUjHa_OhLfNMVU-sUD0l4JKiPe2QwD6DcQwMFTjRy1nSU4RqDM"/>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-1">Medical Corp</div>
                    <div class="text-sm font-bold text-white group-hover:text-alert-cyan">HMS EMERGENCY</div>
                </div>
                <div class="absolute inset-0 bg-obsidian/95 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-center rounded-3xl">
                    <div class="space-y-4 text-left">
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Beds Available</div>
                            <div class="text-xl font-mono text-white">21,092</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Air-Lift Ready</div>
                            <div class="text-xl font-mono text-white">12</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="group relative tactical-glass p-8 rounded-3xl border-white/5 hover:border-alert-cyan/30 transition-all cursor-crosshair">
                <div class="h-20 flex items-center justify-center mb-6">
                    <div class="w-16 h-16 rounded-full border-2 border-white/10 flex items-center justify-center opacity-30">
                        <img class="w-8 h-8 filter invert opacity-60" src="https://lh3.googleusercontent.com/aida/ADBb0uh6avePo0NTzhpJuy7uElSOQj5TUlrSU4fqpLtYUP-tsDZR7SdASoHlvpRbQDilnbzG31jv8JK-gji8sqyJQxibUoFowf3utggTppocgE6X30Y6vpF3v6eT1h_ThG33YJ6sXCFjITgNois-LxmAnvyzNWH7NBBDgK_jz1eaq0oRoDUQ0iYfTTz714C32oKl6NVwc9Rz4E_HZUzG4CUeY7V3kDcHRr3uKYaJP8_-gwbAJ1PJSPNF2fuxow"/>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-[10px] font-bold text-white/30 uppercase tracking-widest mb-1">Civil Defense</div>
                    <div class="text-sm font-bold text-white group-hover:text-alert-cyan">FIRE &amp; HAZMAT</div>
                </div>
                <div class="absolute inset-0 bg-obsidian/95 backdrop-blur-md opacity-0 group-hover:opacity-100 transition-opacity p-6 flex flex-col justify-center rounded-3xl">
                    <div class="space-y-4 text-left">
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Units Ready</div>
                            <div class="text-xl font-mono text-white">412</div>
                        </div>
                        <div>
                            <div class="text-[9px] text-alert-cyan font-bold uppercase">Water Supply</div>
                            <div class="text-xl font-mono text-white">92%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Timeline -->
    <section class="py-32 px-12 lg:px-24 bg-white/2 relative overflow-hidden reveal-stagger">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-alert-cyan/5 blur-[150px] rounded-full"></div>
        <div class="max-w-4xl mx-auto">
            <div class="mb-16">
                <span class="text-alert-cyan font-bold text-[11px] uppercase tracking-[0.3em]">Operational Insight</span>
                <h2 class="font-headline text-4xl font-extrabold text-white mt-2">Rescue in Progress</h2>
            </div>
            <div class="relative pl-12 border-l border-white/10 space-y-24">
                <!-- Step 1 -->
                <div class="relative">
                    <div class="absolute -left-[54px] top-0 w-4 h-4 bg-alert-cyan rounded-full ring-8 ring-alert-cyan/20"></div>
                    <div class="tactical-glass p-8 rounded-3xl border-white/5">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold text-white/50 font-mono">T+00:00:12</span>
                            <span class="bg-green-500/20 text-green-500 px-3 py-1 rounded text-[10px] font-bold uppercase">Signal Intercepted</span>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">Automated SOS Protocol Activated</h4>
                        <p class="text-white/50 text-sm">Satellite detection identified high-velocity structural anomaly in District 4. Coordination node triggered.</p>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative">
                    <div class="absolute -left-[54px] top-0 w-4 h-4 bg-alert-cyan rounded-full ring-8 ring-alert-cyan/10 opacity-60"></div>
                    <div class="tactical-glass p-8 rounded-3xl border-white/5">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold text-white/50 font-mono">T+00:02:45</span>
                            <span class="bg-alert-cyan/20 text-alert-cyan px-3 py-1 rounded text-[10px] font-bold uppercase">Resources Allocated</span>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">Asset Deployment: Air &amp; Land</h4>
                        <p class="text-white/50 text-sm">AI-driven logistics routed 2 medical drones and 1 ground recovery unit. ETA: 4 minutes.</p>
                    </div>
                </div>
                
                <!-- Step 3 (Current) -->
                <div class="relative">
                    <div class="absolute -left-[54px] top-0 w-4 h-4 bg-emergency-red rounded-full animate-ping"></div>
                    <div class="tactical-glass p-8 rounded-3xl border-emergency-red/30 bg-emergency-red/5">
                        <div class="flex justify-between items-start mb-4">
                            <span class="text-xs font-bold text-emergency-red font-mono">CURRENT STATUS</span>
                            <span class="bg-emergency-red/20 text-emergency-red px-3 py-1 rounded text-[10px] font-bold uppercase">Active Extraction</span>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-2">Ground Operation: Multi-Agency Link</h4>
                        <p class="text-white/50 text-sm">NDRF and Local EMS on-site. Encrypted data mesh established between tactical units.</p>
                        <div class="mt-6 w-full bg-white/5 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emergency-red h-full w-[72%] transition-all duration-1000"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-obsidian border-t border-white/5 py-24 px-12 lg:px-24">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-16">
            <div class="md:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <span class="font-headline text-xl font-bold tracking-tighter text-white/50">RESQNET</span>
                </div>
                <p class="text-white/30 text-xs leading-relaxed">
                    The Official Crisis Coordination Platform of the Sovereign Republic. Built on Government-Grade Mesh Infrastructure.
                </p>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-alert-cyan uppercase tracking-[0.2em] mb-6">Strategic Hotlines</h5>
                <ul class="space-y-4">
                    <li><a class="text-neon-orange font-bold text-lg hover:brightness-125 transition-all text-decoration-none" href="#">108 - MEDICAL</a></li>
                    <li><a class="text-neon-orange font-bold text-lg hover:brightness-125 transition-all text-decoration-none" href="#">101 - FIRE RED</a></li>
                    <li><a class="text-white/50 hover:text-white transition-colors text-decoration-none" href="#">Emergency Protocol A-1</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mb-6">Legal &amp; Compliance</h5>
                <ul class="space-y-3">
                    <li><a class="text-xs text-white/50 hover:text-alert-cyan transition-colors text-decoration-none" href="#">Privacy Framework</a></li>
                    <li><a class="text-xs text-white/50 hover:text-alert-cyan transition-colors text-decoration-none" href="#">National Data Security</a></li>
                    <li><a class="text-xs text-white/50 hover:text-alert-cyan transition-colors text-decoration-none" href="#">Audit Logs</a></li>
                </ul>
            </div>
            <div>
                <h5 class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mb-6">Network Node</h5>
                <div class="tactical-glass p-4 rounded-xl border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                        <span class="text-[10px] font-mono text-white/60">Node: ASIA-SOUTH-1</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-24 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
            <span class="text-[10px] text-white/20 font-bold uppercase tracking-widest">© {{ date('Y') }} RESQNET. MINISTRY OF EMERGENCY COORDINATION.</span>
            <div class="flex gap-8">
                <span class="text-[10px] text-white/20 font-bold uppercase tracking-widest cursor-pointer hover:text-white">Twitter / X</span>
                <span class="text-[10px] text-white/20 font-bold uppercase tracking-widest cursor-pointer hover:text-white">Github Sovereign</span>
            </div>
        </div>
    </footer>

    <script>
        // Nav shrink on scroll
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });

        // Intersection Observer for stagger animations
        const observerOptions = { threshold: 0.2 };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.reveal-stagger').forEach((el) => observer.observe(el));
    </script>
</body>
</html>
