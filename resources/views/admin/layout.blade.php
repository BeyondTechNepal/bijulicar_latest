<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Bijulicar</title>

    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap"
        rel="stylesheet">

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 74px;
            --transition-speed: 0.28s;
            --transition-ease: cubic-bezier(0.4, 0, 0.2, 1);
            --sidebar-bg: #0d1117;
            --sidebar-border: rgba(255, 255, 255, 0.06);
            --accent: #6366f1;
            --accent-glow: rgba(99, 102, 241, 0.25);
            --text-primary: #f0f0f0;
            --text-muted: #5a6272;
            --text-secondary: #8b95a5;
            --item-hover: rgba(255, 255, 255, 0.05);
            --active-bg: rgba(99, 102, 241, 0.15);
            --active-border: #6366f1;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f4f5f7;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ─── Overlay for mobile ─── */
        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(2px);
            z-index: 40;
        }

        #sidebar-overlay.visible {
            display: block;
        }

        /* ─── Sidebar ─── */
        #sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 50;
            display: flex;
            flex-direction: column;
            transition: width var(--transition-speed) var(--transition-ease),
                transform var(--transition-speed) var(--transition-ease);
            border-right: 1px solid var(--sidebar-border);
            overflow: hidden;
        }

        /* Subtle noise texture overlay */
        #sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        #sidebar>* {
            position: relative;
            z-index: 1;
        }

        /* Desktop collapsed */
        #sidebar.sidebar-collapsed {
            width: var(--sidebar-collapsed-width);
        }

        /* Mobile hidden */
        @media (max-width: 767px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width) !important;
            }

            #sidebar.mobile-open {
                transform: translateX(0);
            }

            #main-content {
                margin-left: 0 !important;
            }
        }

        /* Main content offset */
        #main-content {
            margin-left: var(--sidebar-width);
            transition: margin-left var(--transition-speed) var(--transition-ease);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        #main-content.content-collapsed {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ─── Hide/show helpers ─── */
        #sidebar.sidebar-collapsed .hide-on-collapse {
            opacity: 0;
            width: 0;
            overflow: hidden;
            pointer-events: none;
            white-space: nowrap;
        }

        #sidebar.sidebar-collapsed .center-on-collapse {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        #sidebar.sidebar-collapsed .logo-area {
            justify-content: center;
        }

        #sidebar.sidebar-collapsed .profile-area {
            justify-content: center;
            padding: 12px 0;
        }

        /* Smooth text fade */
        .hide-on-collapse {
            transition: opacity 0.18s ease, width 0.25s ease;
        }

        /* ─── Nav item ─── */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.18s ease;
            position: relative;
            white-space: nowrap;
            cursor: pointer;
        }

        .nav-item:hover {
            background: var(--item-hover);
            color: var(--text-primary);
        }

        .nav-item.active {
            background: var(--active-bg);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .nav-item.active .nav-icon {
            color: #818cf8;
        }

        .nav-item.active-amber {
            background: rgba(245, 158, 11, 0.12);
            color: #fcd34d;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .nav-item.active-rose {
            background: rgba(244, 63, 94, 0.12);
            color: #fda4af;
            border: 1px solid rgba(244, 63, 94, 0.25);
        }

        .nav-item.active-emerald {
            background: rgba(16, 185, 129, 0.12);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        /* Active left indicator */
        .nav-item.active::before,
        .nav-item.active-amber::before,
        .nav-item.active-rose::before,
        .nav-item.active-emerald::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 60%;
            border-radius: 0 2px 2px 0;
            background: currentColor;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            transition: color 0.18s ease;
        }

        .nav-item:hover .nav-icon {
            color: var(--text-secondary);
        }

        /* Section label */
        .nav-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: 16px 12px 6px;
            font-family: 'DM Mono', monospace;
        }

        /* Badge */
        .badge {
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 20px;
            line-height: 1.4;
            margin-left: auto;
            flex-shrink: 0;
        }

        .badge-amber {
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-rose {
            background: rgba(244, 63, 94, 0.2);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.3);
        }

        .badge-emerald {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        /* Submenu */
        .submenu {
            padding-left: 28px;
            margin-top: 2px;
        }

        .submenu .nav-item {
            font-size: 13px;
            padding: 7px 10px;
            color: #4a5568;
        }

        .submenu .nav-item:hover {
            color: var(--text-primary);
        }

        /* Logo area */
        .logo-area {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 16px 14px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .logo-wordmark {
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .logo-title {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-primary);
            letter-spacing: 0.18em;
        }

        .logo-sub {
            font-size: 9px;
            font-weight: 500;
            color: var(--text-muted);
            letter-spacing: 0.2em;
            text-transform: uppercase;
        }

        /* Toggle button */
        .toggle-btn {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--sidebar-border);
            color: var(--text-muted);
            cursor: pointer;
            flex-shrink: 0;
            transition: all 0.18s ease;
        }

        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-primary);
            border-color: rgba(255, 255, 255, 0.12);
        }

        /* Profile area */
        .profile-area {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: white;
            flex-shrink: 0;
            font-family: 'DM Mono', monospace;
        }

        .role-badge {
            display: inline-flex;
            align-items: center;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 2px 7px;
            border-radius: 4px;
        }

        .role-super {
            background: rgba(239, 68, 68, 0.15);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .role-admin {
            background: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        /* Nav scroll */
        .nav-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.08) transparent;
        }

        .nav-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .nav-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .nav-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 2px;
        }

        /* Footer */
        .sidebar-footer {
            padding: 10px 12px 14px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px 12px;
            border-radius: 8px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 13.5px;
            font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            transition: all 0.18s ease;
            text-align: left;
        }

        .logout-btn:hover {
            background: rgba(244, 63, 94, 0.1);
            color: #f87171;
        }

        /* Tooltip for collapsed state */
        #sidebar.sidebar-collapsed .nav-item {
            position: relative;
        }

        #sidebar.sidebar-collapsed .nav-item:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e2530;
            color: var(--text-primary);
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 6px;
            white-space: nowrap;
            pointer-events: none;
            border: 1px solid var(--sidebar-border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            z-index: 100;
        }

        /* Header */
        #top-header {
            height: 58px;
            background: #fff;
            border-bottom: 1px solid #e8eaed;
            display: flex;
            align-items: center;
            padding: 0 28px;
            gap: 14px;
            position: sticky;
            top: 0;
            z-index: 30;
        }

        .header-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a1d23;
            letter-spacing: -0.01em;
        }

        /* Mobile hamburger */
        #mobile-toggle {
            display: none;
            width: 34px;
            height: 34px;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #f4f5f7;
            border: 1px solid #e2e4e8;
            color: #5a6272;
            cursor: pointer;
            flex-shrink: 0;
        }

        @media (max-width: 767px) {
            #mobile-toggle {
                display: flex;
            }
        }

        /* Divider */
        .nav-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 8px 0;
        }

        /* ─── Icons ─── */
        svg {
            display: block;
        }

        /* Submenu arrow transition */
        .submenu-arrow {
            transition: transform 0.2s ease;
            margin-left: auto;
            flex-shrink: 0;
        }

        .submenu-arrow.open {
            transform: rotate(90deg);
        }

        /* Collapsed: hide submenu arrows and section labels */
        #sidebar.sidebar-collapsed .nav-section-label,
        #sidebar.sidebar-collapsed .submenu-arrow {
            display: none;
        }

        /* Ensure icon color inherits properly for active states */
        .nav-item.active .nav-icon,
        .nav-item.active-amber .nav-icon,
        .nav-item.active-rose .nav-icon,
        .nav-item.active-emerald .nav-icon {
            color: currentColor;
        }
    </style>
</head>

<body>
    <!-- Mobile overlay -->
    <div id="sidebar-overlay" onclick="closeMobileSidebar()"></div>

    <div class="flex min-h-screen">
        @php $admin = auth('admin')->user(); @endphp

        <!-- ═══════════════════════════════════════
             SIDEBAR
        ════════════════════════════════════════ -->
        <aside id="sidebar">

            <!-- Logo -->
            <div class="logo-area">
                <div class="logo-wordmark hide-on-collapse">
                    <div class="logo-title">BIJULICAR</div>
                    <div class="logo-sub">Admin Console</div>
                </div>
                <button onclick="toggleSidebar()" class="toggle-btn" aria-label="Toggle sidebar">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
            </div>

            <!-- Profile -->
            <div class="profile-area">
                <div class="profile-avatar">{{ substr($admin->name ?? 'A', 0, 2) }}</div>
                <div class="hide-on-collapse" style="min-width:0; flex:1;">
                    <div
                        style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $admin->name }}</div>
                    <div style="margin-top:3px;">
                        <span class="role-badge {{ $admin->hasRole('superadmin') ? 'role-super' : 'role-admin' }}">
                            {{ $admin->getRoleNames()->first() }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="nav-scroll">

                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard"
                    class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="nav-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="3" width="7" height="7" rx="1" />
                            <rect x="14" y="14" width="7" height="7" rx="1" />
                            <rect x="3" y="14" width="7" height="7" rx="1" />
                        </svg>
                    </span>
                    <span class="hide-on-collapse">Dashboard</span>
                </a>

                @can('manage users')
                    <div class="nav-section-label">Management</div>

                    <a href="{{ route('admin.users') }}" data-tooltip="Users"
                        class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 00-3-3.87" />
                                <path d="M16 3.13a4 4 0 010 7.75" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Users</span>
                    </a>

                    <a href="{{ route('admin.verifications.index') }}" data-tooltip="Verifications"
                        class="nav-item {{ request()->routeIs('admin.verifications*') ? 'active-amber' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                <polyline points="9 12 11 14 15 10" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse" style="flex:1;">Verifications</span>
                        @php
                            $pendingCount =
                                \App\Models\SellerVerification::where('status', 'pending')->count() +
                                \App\Models\BusinessVerification::where('status', 'pending')->count() +
                                \App\Models\StationVerification::where('status', 'pending')->count() +
                                \App\Models\GarageVerification::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span class="badge badge-amber hide-on-collapse">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.permissions.index') }}" data-tooltip="Permissions"
                        class="nav-item {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                                <path d="M7 11V7a5 5 0 0110 0v4" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Permissions</span>
                    </a>

                    <a href="{{ route('admin.roles.index') }}" data-tooltip="Roles"
                        class="nav-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M6 20v-2a4 4 0 018 0v2" />
                                <path d="M18 12l2 2-4 4" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Roles</span>
                    </a>
                @endcan

                @can('manage admins')
                    <a href="{{ route('admin.admins.index') }}" data-tooltip="Admins"
                        class="nav-item {{ request()->routeIs('admin.admins*') ? 'active-rose' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse" style="flex:1;">Admins</span>
                        <span class="badge badge-rose hide-on-collapse">Super</span>
                    </a>
                @endcan

                @can('manage admin roles')
                    <a href="{{ route('admin.admin_roles.index') }}" data-tooltip="Admin Roles"
                        class="nav-item {{ request()->routeIs('admin.admin_roles*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon
                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Admin Roles</span>
                    </a>
                @endcan

                @can('manage admin permissions')
                    <a href="{{ route('admin.admin_permissions.index') }}" data-tooltip="Admin Permissions"
                        class="nav-item {{ request()->routeIs('admin.admin_permissions*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Admin Permissions</span>
                    </a>
                @endcan

                @can("only news [by only 'news' admin]")
                    <div class="nav-divider hide-on-collapse"></div>
                    <div x-data="{ open: {{ request()->routeIs('admin.news*') || request()->routeIs('admin.news_banner*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="nav-item w-full {{ request()->routeIs('admin.news*') || request()->routeIs('admin.news_banner*') ? 'active' : '' }}"
                            data-tooltip="News">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2" />
                                    <path d="M18 14h-8" />
                                    <path d="M15 18h-5" />
                                    <path d="M10 6h8v4h-8z" />
                                </svg>
                            </span>
                            <span class="hide-on-collapse" style="flex:1;text-align:left;">News</span>
                            <svg :class="open ? 'open' : ''" class="submenu-arrow" width="12" height="12"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="submenu">
                            @can('manage news banners')
                                <a href="{{ route('admin.news_categories.index') }}"
                                    class="nav-item {{ request()->routeIs('admin.news_categories.index') ? 'active' : '' }}">
                                    <span
                                        style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                    <span class="hide-on-collapse">News Categories</span>
                                </a>
                            @endcan

                            <a href="{{ route('admin.news.index') }}"
                                class="nav-item {{ request()->routeIs('admin.news.index') ? 'active' : '' }}">
                                <span
                                    style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                <span class="hide-on-collapse">News Articles</span>
                            </a>

                            @can('manage news banner')
                                <a href="{{ route('admin.news_banner.index') }}"
                                    class="nav-item {{ request()->routeIs('admin.news_banner*') ? 'active' : '' }}">
                                    <span
                                        style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                    <span class="hide-on-collapse">News Banner</span>
                                </a>
                            @endcan

                            <a href="{{ route('admin.newsletter.form') }}"
                                class="nav-item {{ request()->routeIs('admin.newsletter.form') ? 'active' : '' }}">
                                <span
                                    style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                <span class="hide-on-collapse">Newsletter</span>
                            </a>
                        </div>
                    </div>
                @endcan

                @can('map locations')
                    <div class="nav-divider hide-on-collapse"></div>
                    <div class="nav-section-label">Locations</div>
                    <a href="{{ route('admin.locations.index') }}" data-tooltip="Map Locations"
                        class="nav-item {{ request()->routeIs('admin.locations*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polygon points="3 11 22 2 13 21 11 13 3 11" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Map Locations</span>
                    </a>
                @endcan

                @can('map requests')
                    <a href="{{ route('admin.map_locations.index') }}" data-tooltip="Map Requests"
                        class="nav-item {{ request()->routeIs('admin.map_locations*') ? 'active-emerald' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse" style="flex:1;">Map Requests</span>
                        @php $pendingMapCount = \App\Models\NewLocation::where('is_active', false)->count(); @endphp
                        @if ($pendingMapCount > 0)
                            <span class="badge badge-emerald hide-on-collapse">{{ $pendingMapCount }}</span>
                        @endif
                    </a>
                @endcan

                @can('advertisements')
                    <div class="nav-divider hide-on-collapse"></div>
                    <div class="nav-section-label">Monetisation</div>
                    <a href="{{ route('admin.advertisements.index') }}" data-tooltip="Advertisements"
                        class="nav-item {{ request()->routeIs('admin.advertisements*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                <path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse" style="flex:1;">Advertisements</span>
                        @php $pendingAds = \App\Models\Advertisement::where('status', 'pending_review')->count(); @endphp
                        @if ($pendingAds > 0)
                            <span class="badge badge-amber hide-on-collapse">{{ $pendingAds }}</span>
                        @endif
                    </a>
                @endcan

                @can('ad pricing')
                    <a href="{{ route('admin.ad-pricing.index') }}" data-tooltip="Ad Pricing"
                        class="nav-item {{ request()->routeIs('admin.ad-pricing*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Ad Pricing</span>
                    </a>
                @endcan

                @can('revenue')
                    <a href="{{ route('admin.revenue.index') }}" data-tooltip="Revenue"
                        class="nav-item {{ request()->routeIs('admin.revenue*') ? 'active-emerald' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="23 6 13.5 15.5 8.5 10.5 1 18" />
                                <polyline points="17 6 23 6 23 12" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Revenue</span>
                    </a>
                @endcan

                @can('contact control')
                    <div class="nav-divider hide-on-collapse"></div>
                    <div class="nav-section-label">Communication</div>

                    <div x-data="{ open: {{ request()->routeIs('admin.contact_*') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                            class="nav-item w-full {{ request()->routeIs('admin.contact_*') ? 'active' : '' }}"
                            data-tooltip="Contact">
                            <span class="nav-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.03 1.19 2 2 0 012 .03h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 14.92z" />
                                </svg>
                            </span>
                            <span class="hide-on-collapse" style="flex:1;text-align:left;">Contact</span>
                            <svg :class="open ? 'open' : ''" class="submenu-arrow" width="12" height="12"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 18 15 12 9 6" />
                            </svg>
                        </button>

                        <div x-show="open" x-collapse class="submenu">
                            <a href="{{ route('admin.contact_banner.index') }}"
                                class="nav-item {{ request()->routeIs('admin.contact_banner*') ? 'active' : '' }}">
                                <span
                                    style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                <span class="hide-on-collapse">Contact Banner</span>
                            </a>
                            <a href="{{ route('admin.contact_details.index') }}"
                                class="nav-item {{ request()->routeIs('admin.contact_details*') ? 'active' : '' }}">
                                <span
                                    style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                <span class="hide-on-collapse">Contact Details</span>
                            </a>
                            <a href="{{ route('admin.contact_messages.index') }}"
                                class="nav-item {{ request()->routeIs('admin.contact_messages*') ? 'active' : '' }}">
                                <span
                                    style="width:6px;height:6px;border-radius:50%;background:currentColor;flex-shrink:0;opacity:0.5;"></span>
                                <span class="hide-on-collapse">Messages</span>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('admin.social-links.index') }}" data-tooltip="Social Links"
                        class="nav-item {{ request()->routeIs('admin.social-links.*') ? 'active' : '' }}">
                        <span class="nav-icon">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="18" cy="5" r="3" />
                                <circle cx="6" cy="12" r="3" />
                                <circle cx="18" cy="19" r="3" />
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" />
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Social Links</span>
                    </a>
                @endcan

            </nav>

            <!-- Footer / Logout -->
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn center-on-collapse">
                        <span class="nav-icon" style="color:inherit;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                                <polyline points="16 17 21 12 16 7" />
                                <line x1="21" y1="12" x2="9" y2="12" />
                            </svg>
                        </span>
                        <span class="hide-on-collapse">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- ═══════════════════════════════════════
             MAIN CONTENT
        ════════════════════════════════════════ -->
        <div id="main-content" class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header id="top-header">
                <!-- Mobile toggle -->
                <button id="mobile-toggle" onclick="openMobileSidebar()" aria-label="Open menu">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <line x1="3" y1="12" x2="21" y2="12" />
                        <line x1="3" y1="18" x2="21" y2="18" />
                    </svg>
                </button>
                <h1 class="header-title">@yield('page-title', 'Dashboard')</h1>
            </header>

            <!-- Page Content -->
            <main style="padding:28px; flex:1;">
                @if (session('success'))
                    <div style="margin-bottom:20px; background:#f0fdf4; border-left:3px solid #22c55e; padding:12px 16px; border-radius:6px; display:flex; gap:10px; align-items:flex-start;"
                        role="alert">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e"
                            stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                            style="flex-shrink:0;margin-top:1px;">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#15803d;">Success</p>
                            <p style="font-size:12.5px;color:#166534;margin-top:2px;">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // ── Desktop toggle ──
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('main-content');
            const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
            content.classList.toggle('content-collapsed', isCollapsed);
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
        }

        // ── Mobile open ──
        function openMobileSidebar() {
            document.getElementById('sidebar').classList.add('mobile-open');
            document.getElementById('sidebar-overlay').classList.add('visible');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            document.getElementById('sidebar').classList.remove('mobile-open');
            document.getElementById('sidebar-overlay').classList.remove('visible');
            document.body.style.overflow = '';
        }

        // ── Init from localStorage (desktop only) ──
        document.addEventListener('DOMContentLoaded', () => {
            const isMobile = window.innerWidth < 768;
            if (!isMobile && localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                document.getElementById('sidebar').classList.add('sidebar-collapsed');
                document.getElementById('main-content').classList.add('content-collapsed');
            }
        });

        // ── Close mobile sidebar on resize ──
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 768) {
                closeMobileSidebar();
                document.body.style.overflow = '';
            }
        });
    </script>
</body>

</html>
