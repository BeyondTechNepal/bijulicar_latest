<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Bijulicar</title>

    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    {{-- import for alphine --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom transitions for the sidebar and text */
        .sidebar-animate {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-collapsed {
            width: 4.5rem !important;
        }

        .sidebar-collapsed .hide-on-collapse {
            display: none;
        }

        .sidebar-collapsed .center-on-collapse {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen overflow-x-hidden">
    <div class="flex min-h-screen">
        @php $admin = auth('admin')->user(); @endphp

        <aside id="sidebar" class="sidebar-animate w-64 bg-gray-900 flex flex-col sticky top-0 h-screen z-50">

            <div class="px-5 py-5 border-b border-gray-700 flex items-center justify-between">
                <div class="hide-on-collapse">
                    <div class="text-white font-bold text-sm tracking-tight">BIJULICAR</div>
                    <div class="text-gray-500 text-[10px] font-mono leading-none">ADMIN CONSOLE</div>
                </div>
                <button onclick="toggleSidebar()"
                    class="text-gray-400 hover:text-white p-1 rounded bg-gray-800 border border-gray-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <div class="px-5 py-4 border-b border-gray-700 hide-on-collapse">
                <div class="text-sm text-white font-medium truncate">{{ $admin->name }}</div>
                <span
                    class="inline-block mt-1 text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider
                    {{ $admin->hasRole('superadmin') ? 'bg-red-900/50 text-red-300 border border-red-800' : 'bg-amber-900/50 text-amber-300 border border-amber-800' }}">
                    {{ $admin->getRoleNames()->first() }}
                </span>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                   {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="font-bold w-5 text-center">D</span>
                    <span class="hide-on-collapse">Dashboard</span>
                </a>

                @can('manage users')
                    <div class="pt-4 pb-2 px-3 hide-on-collapse">
                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.2em]">Management</p>
                    </div>

                    <a href="{{ route('admin.users') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.users*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">U</span>
                        <span class="hide-on-collapse">Users</span>
                    </a>

                    <a href="{{ route('admin.verifications.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.verifications*') ? 'bg-amber-600 text-white shadow-lg shadow-amber-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">V</span>
                        <span class="hide-on-collapse flex-1">Verifications</span>
                        @php
                            $pendingCount =
                                \App\Models\SellerVerification::where('status', 'pending')->count() +
                                \App\Models\BusinessVerification::where('status', 'pending')->count() +
                                \App\Models\StationVerification::where('status', 'pending')->count() +
                                \App\Models\GarageVerification::where('status', 'pending')->count();
                        @endphp
                        @if ($pendingCount > 0)
                            <span
                                class="hide-on-collapse text-[10px] bg-amber-500 text-white px-1.5 py-0.5 rounded-full font-black">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('admin.permissions.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.permissions*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">P</span>
                        <span class="hide-on-collapse">Permissions</span>
                    </a>

                    <a href="{{ route('admin.roles.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.roles*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">R</span>
                        <span class="hide-on-collapse">Roles</span>
                    </a>
                @endcan

                @can('manage admins')
                    <a href="{{ route('admin.admins.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.admins*') ? 'bg-rose-600 text-white shadow-lg shadow-rose-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center text-rose-400 group-hover:text-white">A</span>
                        <span class="hide-on-collapse flex-1">Admins</span>
                        <span
                            class="hide-on-collapse text-[10px] bg-rose-900/50 text-rose-300 px-1.5 py-0.5 rounded border border-rose-800">Super</span>
                    </a>
                @endcan

                @can('manage admin roles')
                    {{-- admin roles --}}
                    <a href="{{ route('admin.admin_roles.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.admin_roles*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">R</span>
                        <span class="hide-on-collapse">Admin Roles</span>
                    </a>
                @endcan

                @can('manage admin permissions')
                    {{-- admin permissions --}}
                    <a href="{{ route('admin.admin_permissions.index') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.admin_permissions*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                        <span class="font-bold w-5 text-center">P</span>
                        <span class="hide-on-collapse">Admin Permissions</span>
                    </a>
                @endcan

                @can("only news [by only 'news' admin]")
                    <div x-data="{ open: {{ request()->routeIs('admin.news*') || request()->routeIs('admin.news_banner*') ? 'true' : 'false' }} }">

                        <!-- Parent -->
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition-all
        {{ request()->routeIs('admin.news*') || request()->routeIs('admin.news_banner*')
            ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20'
            : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

                            <div class="flex items-center gap-3">
                                <span class="font-bold w-5 text-center">N</span>
                                <span class="hide-on-collapse">News</span>
                            </div>

                            <!-- Arrow -->
                            <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform hide-on-collapse"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Children -->
                        <div x-show="open" x-collapse class="mt-1 ml-6 space-y-1">

                            {{-- the permission is also for news category management --}}
                            @can('manage news banners')
                                <a href="{{ route('admin.news_categories.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.news_categories.index') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <span class="w-5 text-center">•</span>
                                    <span class="hide-on-collapse">News Categories</span>
                                </a>
                            @endcan

                            <a href="{{ route('admin.news.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.news.index') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <span class="w-5 text-center">•</span>
                                <span class="hide-on-collapse">News Articles</span>
                            </a>

                            @can('manage news banner')
                                <a href="{{ route('admin.news_banner.index') }}"
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.news_banner*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                    <span class="w-5 text-center">•</span>
                                    <span class="hide-on-collapse">News Banner</span>
                                </a>
                            @endcan

                            <a href="{{ route('admin.newsletter.form') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.newsletter.form') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <span class="w-5 text-center">•</span>
                                <span class="hide-on-collapse">News Letter</span>
                            </a>
                        </div>
                    </div>
                @endcan

                <a href="{{ route('admin.locations.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.locations*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="font-bold w-5 text-center">M</span>
                    <span class="hide-on-collapse">Map Locations</span>
                </a>

                <a href="{{ route('admin.map_locations.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.map_locations*') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="font-bold w-5 text-center">📍</span>
                    <span class="hide-on-collapse flex-1">Map Requests</span>
                    @php $pendingMapCount = \App\Models\NewLocation::where('is_active', false)->count(); @endphp
                    @if ($pendingMapCount > 0)
                        <span
                            class="hide-on-collapse text-[10px] font-black bg-emerald-500 text-white px-1.5 py-0.5 rounded-full min-w-[18px] text-center">
                            {{ $pendingMapCount }}
                        </span>
                    @endif
                </a>

                {{-- Advertisements --}}
                <a href="{{ route('admin.advertisements.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.advertisements*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="font-bold w-5 text-center">A</span>
                    <span class="hide-on-collapse flex-1">Advertisements</span>
                    @php $pendingAds = \App\Models\Advertisement::where('status', 'pending_review')->count(); @endphp
                    @if ($pendingAds > 0)
                        <span
                            class="hide-on-collapse text-[10px] bg-amber-500 text-white px-1.5 py-0.5 rounded-full font-black">{{ $pendingAds }}</span>
                    @endif
                </a>

                {{-- Ad Pricing --}}
                <a href="{{ route('admin.ad-pricing.index') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.ad-pricing*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <span class="font-bold w-5 text-center">$</span>
                    <span class="hide-on-collapse">Ad Pricing</span>
                </a>

                @can('contact control')
                    <div x-data="{ open: {{ request()->routeIs('admin.contact_*') ? 'true' : 'false' }} }">

                        <!-- Parent -->
                        <button @click="open = !open"
                            class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('admin.contact_*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">

                            <div class="flex items-center gap-3">
                                <span class="font-bold w-5 text-center">C</span>
                                <span class="hide-on-collapse">Contact</span>
                            </div>

                            <!-- Arrow -->
                            <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform hide-on-collapse"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <!-- Children -->
                        <div x-show="open" x-collapse class="mt-1 ml-6 space-y-1">

                            <a href="{{ route('admin.contact_banner.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all
            {{ request()->routeIs('admin.contact_banner*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <span class="w-5 text-center">•</span>
                                <span class="hide-on-collapse">Contact Banner</span>
                            </a>

                            <a href="{{ route('admin.contact_details.index') }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->routeIs('admin.contact_details*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <span class="w-5 text-center">•</span>
                                <span class="hide-on-collapse">Contact Details</span>
                            </a>

                            <a href="{{ route('admin.contact_messages.index') }}"
                                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-all center-on-collapse
                       {{ request()->routeIs('admin.contact_messages*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-900/20' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                                <span class="font-bold w-5 text-center">C</span>
                                <span class="hide-on-collapse">Contact Messages</span>
                            </a>

                        </div>
                    </div>
                @endcan
            </nav>

            <div class="p-3 border-t border-gray-800">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-500 hover:bg-red-900/20 hover:text-red-400 transition-all center-on-collapse">
                        <span class="hide-on-collapse font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-gray-200 h-16 flex items-center px-8 sticky top-0 z-40">
                <h1 class="text-lg font-bold text-gray-800 tracking-tight">@yield('page-title', 'Dashboard')</h1>
            </header>

            <main class="p-8">
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 p-4 shadow-sm"
                        role="alert">
                        <p class="text-sm font-bold">Success</p>
                        <p class="text-xs">{{ session('success') }}</p>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-collapsed');

            // Persist the user's preference
            const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
        }

        // Initialize state from local storage
        document.addEventListener('DOMContentLoaded', () => {
            if (localStorage.getItem('admin_sidebar_collapsed') === 'true') {
                document.getElementById('sidebar').classList.add('sidebar-collapsed');
            }
        });
    </script>
</body>

</html>
