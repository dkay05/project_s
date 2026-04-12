<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — EMS</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: #0f1117;
            color: #e8eaf0;
        }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f1117; }
        ::-webkit-scrollbar-thumb { background: #2a2f42; border-radius: 3px; }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            border-radius: 8px;
            color: #7a8099;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        .sidebar-link:hover {
            background: rgba(245,166,35,0.08);
            color: #e8eaf0;
        }
        .sidebar-link.active {
            background: rgba(245,166,35,0.12);
            color: #f5a623;
        }

        .ems-input {
            background: #1e2333;
            border: 1px solid #2a2f42;
            border-radius: 10px;
            padding: 12px 14px;
            color: #e8eaf0;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            width: 100%;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .ems-input:focus {
            outline: none;
            border-color: #f5a623;
            box-shadow: 0 0 0 3px rgba(245,166,35,0.1);
        }
        .ems-input::placeholder { color: #7a8099; }

        .ems-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #7a8099;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .ems-btn {
            background: #f5a623;
            color: #0f1117;
            border: none;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .ems-btn:hover { background: #c47d0e; }

        .ems-btn-outline {
            background: transparent;
            color: #e8eaf0;
            border: 1px solid #2a2f42;
            border-radius: 10px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .ems-btn-outline:hover { border-color: #f5a623; color: #f5a623; }

        .ems-card {
            background: #181c27;
            border: 1px solid #2a2f42;
            border-radius: 14px;
            padding: 24px;
        }

        select.ems-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%237a8099' viewBox='0 0 16 16'%3E%3Cpath d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            padding-right: 36px;
        }
        select.ems-input option { background: #1e2333; color: #e8eaf0; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .active-badge { background: rgba(62,207,142,0.15); color: #3ecf8e; }
        .inactive-badge { background: rgba(224,82,82,0.15); color: #e05252; }
    </style>
</head>
<body>
    <div style="display:flex; min-height:100vh;">

        <!-- SIDEBAR -->
        <aside style="width:240px; background:#181c27; border-right:1px solid #2a2f42; position:fixed; top:0; left:0; bottom:0; display:flex; flex-direction:column; z-index:50;">
            <!-- Logo -->
            <div style="padding:24px 20px; border-bottom:1px solid #2a2f42;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:36px; height:36px; background:#f5a623; border-radius:8px; display:flex; align-items:center; justify-content:center;">
                        <svg width="20" height="20" fill="#0f1117" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:16px; color:#e8eaf0;">AdminPanel</div>
                        <div style="font-size:10px; color:#7a8099; text-transform:uppercase; letter-spacing:1px;">Administrator</div>
                    </div>
                </div>
            </div>

            <!-- Nav -->
            <nav style="padding:16px 12px; flex:1;">
                <div style="font-size:10px; color:#7a8099; text-transform:uppercase; letter-spacing:1px; padding:0 16px 8px; font-weight:600;">Main Menu</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.employees.index') }}" class="sidebar-link {{ request()->routeIs('admin.employees.*') ? 'active' : '' }}">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    Employee
                </a>
                <a href="#" class="sidebar-link" style="opacity:0.5;">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 4h5v8l-2.5-1.5L6 12V4z"/></svg>
                    LMS <span style="font-size:10px; color:#7a8099; margin-left:auto;">Soon</span>
                </a>
            </nav>

            <!-- Bottom User -->
            <div style="padding:16px 12px; border-top:1px solid #2a2f42;">
                <div style="display:flex; align-items:center; gap:10px; padding:8px;">
                    <div style="width:34px; height:34px; background:#f5a623; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#0f1117;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:13px; font-weight:600;">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div style="font-size:11px; color:#7a8099;">Admin</div>
                    </div>
                    <a href="{{ route('logout') }}" style="color:#7a8099; transition:color 0.2s;" onmouseover="this.style.color='#e05252'" onmouseout="this.style.color='#7a8099'">
                        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
                    </a>
                </div>
            </div>
        </aside>

        <!-- MAIN -->
        <div style="flex:1; margin-left:240px;">
            <!-- TOPBAR -->
            <header style="height:70px; background:#0f1117; border-bottom:1px solid #2a2f42; position:sticky; top:0; z-index:40; display:flex; align-items:center; justify-content:space-between; padding:0 32px;">
                <h1 style="font-size:20px; font-weight:700;">@yield('page-title', 'Dashboard')</h1>
                <div style="display:flex; align-items:center; gap:16px;">
                    <button style="background:transparent; border:none; color:#7a8099; cursor:pointer; position:relative;">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/></svg>
                        <span style="position:absolute; top:-2px; right:-2px; width:8px; height:8px; background:#e05252; border-radius:50;"></span>
                    </button>
                    <div style="width:36px; height:36px; background:#f5a623; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; color:#0f1117;">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                </div>
            </header>

            <main style="padding:32px;">
                @yield('content')
            </main>
        </div>
    </div>

    @if(session('success'))
    <script>
        Swal.fire({ icon: 'success', title: '{!! session("success") !!}', timer: 2500, showConfirmButton: false, background: '#181c27', color: '#e8eaf0' });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({ icon: 'error', title: '{!! session("error") !!}', background: '#181c27', color: '#e8eaf0' });
    </script>
    @endif

    @stack('scripts')
</body>
</html>
