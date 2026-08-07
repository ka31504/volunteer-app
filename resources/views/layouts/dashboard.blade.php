<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'Thiện Nguyện Hub') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #3ecf8e;
            --primary-deep: #24b47e;
            --ink: #171717;
            --ink-mute: #707070;
            --ink-faint: #b2b2b2;
            --canvas: #ffffff;
            --canvas-soft: #fafafa;
            --hairline: #dfdfdf;
            --hairline-cool: #ededed;
            --r-sm: 6px;
            --r-md: 8px;
            --r-lg: 12px;
            --sidebar-w: 220px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--canvas-soft);
            color: var(--ink);
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* LAYOUT */
        .db-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .db-sidebar {
            width: var(--sidebar-w);
            background: var(--canvas);
            border-right: 1px solid var(--hairline-cool);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            transition: transform 0.25s ease;
        }

        .db-sidebar.collapsed {
            transform: translateX(calc(-1 * var(--sidebar-w)));
        }

        .sidebar-logo {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--hairline-cool);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 500;
            color: var(--ink);
            text-decoration: none;
        }

        .logo-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--primary);
            display: inline-block;
            flex-shrink: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 12px 0;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 500;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 12px 20px 6px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 20px;
            font-size: 13px;
            font-weight: 400;
            color: var(--ink-mute);
            text-decoration: none;
            border-radius: 0;
            transition: background 0.1s, color 0.1s;
            position: relative;
        }

        .nav-item:hover {
            background: var(--canvas-soft);
            color: var(--ink);
        }

        .nav-item.active {
            background: #f0fdf8;
            color: var(--primary);
            font-weight: 500;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 4px;
            bottom: 4px;
            width: 3px;
            background: var(--primary);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            font-size: 15px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--hairline-cool);
            font-size: 12px;
            color: var(--ink-mute);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--primary);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
        }

        .user-role {
            font-size: 11px;
            color: var(--ink-mute);
        }

        /* MAIN */
        .db-main {
            flex: 1;
            min-width: 0;
            margin-left: var(--sidebar-w);
            transition: margin-left 0.25s ease;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        html {
            overflow-x: hidden;
        }

        .db-main.expanded {
            margin-left: 0;
        }

        /* TOPBAR */
        .db-topbar {
            background: var(--canvas);
            border-bottom: 1px solid var(--hairline-cool);
            padding: 0 28px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px;
            border-radius: var(--r-sm);
            color: var(--ink-mute);
            display: flex;
            align-items: center;
        }

        .toggle-btn:hover {
            background: var(--canvas-soft);
            color: var(--ink);
        }

        .topbar-title {
            font-size: 14px;
            font-weight: 500;
            color: var(--ink);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .topbar-logout {
            font-size: 13px;
            color: var(--ink-mute);
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid var(--hairline);
            border-radius: var(--r-sm);
            transition: border-color 0.15s;
        }

        .topbar-logout:hover {
            border-color: var(--ink);
            color: var(--ink);
        }

        /* CONTENT */
        .db-content {
            flex: 1;
            padding: 28px;
        }

        /* OVERLAY mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 39;
        }

        @media (max-width: 768px) {
            .db-sidebar {
                transform: translateX(calc(-1 * var(--sidebar-w)));
            }

            .db-sidebar.open {
                transform: translateX(0);
            }

            .db-main {
                margin-left: 0 !important;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="db-wrapper" id="dbWrapper">

        {{-- SIDEBAR --}}
        <aside class="db-sidebar" id="sidebar">
            <a href="{{ route('dashboard') }}" class="sidebar-logo">
                <span class="logo-dot"></span> Thiện Nguyện Hub
            </a>

            <nav class="sidebar-nav">
                <div class="nav-section-label">Tổng quan</div>
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> Dashboard
                </a>

                <div class="nav-section-label">Quản lý</div>
                <a href="{{ route('projects.index') }}"
                    class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <span class="nav-icon">📋</span> Dự án
                </a>
                <a href="{{ route('participants.index') }}"
                    class="nav-item {{ request()->routeIs('participants.*') ? 'active' : '' }}">
                    <span class="nav-icon">👥</span> Tình nguyện viên
                </a>
                <a href="{{ route('donations.index') }}"
                    class="nav-item {{ request()->routeIs('donations.*') ? 'active' : '' }}">
                    <span class="nav-icon">💰</span> Đóng góp
                </a>
                <a href="{{ route('sponsors.index') }}"
                    class="nav-item {{ request()->routeIs('sponsors.*') ? 'active' : '' }}">
                    <span class="nav-icon">🤝</span> Nhà tài trợ
                </a>

                @if(in_array(Auth::user()->role, ['admin', 'editor']))
                <div class="nav-section-label">Báo cáo</div>
                <a href="{{ route('statistics.index') }}" class="nav-item">
                    <span class="nav-icon">📈</span> Thống kê
                </a>
                <a href="{{ route('statistics.export-pdf', ['year' => request('year', now()->year)]) }}"
                    class="nav-item">
                    <span class="nav-icon">📄</span> Xuất PDF
                </a>
                @endif

                @if(Auth::user()->role === 'admin')
                <div class="nav-section-label">Hệ thống</div>
                <a href="{{ route('users.index') }}"
                    class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="nav-icon">⚙️</span> Cài đặt
                </a>
                @endif
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 2)) }}</div>
                    <div>
                        <div class="user-name">{{ Auth::user()->name }}</div>
                        <div class="user-role">
                            @if(Auth::user()->role === 'admin') Quản trị viên
                            @elseif(Auth::user()->role === 'editor') Biên tập viên
                            @else Người dùng
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- OVERLAY --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- MAIN --}}
        <div class="db-main" id="dbMain">

            {{-- TOPBAR --}}
            <header class="db-topbar">
                <div class="topbar-left">
                    <button class="toggle-btn" id="toggleBtn" title="Toggle sidebar">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path d="M3 12h18M3 6h18M3 18h18" />
                        </svg>
                    </button>
                    <span class="topbar-title">@yield('title', 'Dashboard')</span>
                </div>
                <div class="topbar-right">
                    <form method="POST" action="{{ route('logout') }}" style="display:inline">
                        @csrf
                        <button type="submit" class="topbar-logout">Đăng xuất</button>
                    </form>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="db-content">
                @yield('content')
            </main>

        </div>
    </div>

    <script>
        const sidebar = document.getElementById('sidebar');
        const dbMain = document.getElementById('dbMain');
        const overlay = document.getElementById('sidebarOverlay');
        const btn = document.getElementById('toggleBtn');
        const MOBILE = () => window.innerWidth <= 768;

        // Restore state
        if (!MOBILE() && localStorage.getItem('sidebarCollapsed') === 'true') {
            sidebar.classList.add('collapsed');
            dbMain.classList.add('expanded');
        }

        btn.addEventListener('click', () => {
            if (MOBILE()) {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('show');
            } else {
                const collapsed = sidebar.classList.toggle('collapsed');
                dbMain.classList.toggle('expanded', collapsed);
                localStorage.setItem('sidebarCollapsed', collapsed);
            }
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        });
    </script>

    @stack('scripts')
</body>

</html>