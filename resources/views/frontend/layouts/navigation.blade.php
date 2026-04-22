<header class="fixed top-0 left-0 w-full flex justify-center pt-4 md:pt-6 z-50">
    <nav class="w-[92%] max-w-7xl bg-white/80 backdrop-blur-xl border border-white/40 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.1)] px-4 md:px-6 py-3 flex items-center justify-between transition-all duration-300">

        {{-- Left: main nav links --}}
        <div class="hidden lg:flex items-center">
            <div class="flex items-center text-[12px] xl:text-[14px] font-bold text-slate-800 whitespace-nowrap">
                <a href="{{ route('marketplace') }}"
                    class="px-2 xl:px-4 py-2 rounded-xl transition-all {{ Route::is('marketplace') ? 'text-green-600 bg-green-50/50' : 'hover:bg-slate-50' }}">
                    Marketplace
                </a>
                <a href="{{ route('news') }}"
                    class="px-2 xl:px-4 py-2 rounded-xl transition-all {{ Route::is('news') ? 'text-green-600 bg-green-50/50' : 'hover:bg-slate-50' }}">
                    News
                </a>
                <a href="{{ route('loan_calculator') }}"
                    class="px-2 xl:px-4 py-2 rounded-xl transition-all {{ Route::is('loan_calculator') ? 'text-green-600 bg-green-50/50' : 'hover:bg-slate-50' }}">
                    Loan Calculator
                </a>
                <a href="{{ route('compare_cars') }}"
                    class="px-2 xl:px-4 py-2 rounded-xl transition-all {{ Route::is('compare_cars') ? 'text-green-600 bg-green-50/50' : 'hover:bg-slate-50' }}">
                    Compare Cars
                </a>
            </div>
        </div>

        {{-- Centre: logo --}}
        <div class="flex items-center shrink-0 px-2 xl:px-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
                <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center group-hover:bg-[#16a34a] transition-all duration-500 shadow-lg group-hover:rotate-[360deg]">
                    <span class="text-white font-bold text-sm italic">BC</span>
                </div>
                <span class="text-lg md:text-xl font-extrabold tracking-tighter text-slate-900 uppercase">bijuli<span class="text-[#16a34a]">car</span></span>
            </a>
        </div>

        {{-- Right: secondary nav + auth --}}
        <div class="flex items-center space-x-2 md:space-x-4">
            <div class="hidden lg:flex items-center space-x-0 xl:space-x-2">
                <a href="{{ route('businesses.index') }}" class="px-2 xl:px-3 py-2 rounded-xl text-[12px] xl:text-[14px] font-bold {{ Route::is('businesses.*') ? 'text-green-600 bg-green-50' : 'text-slate-800 hover:bg-slate-50' }}">
                    Businesses
                </a>
                <a href="{{ route('map_location') }}" class="px-2 xl:px-3 py-2 rounded-xl text-[12px] xl:text-[14px] font-bold {{ Route::is('map_location') ? 'text-green-600 bg-green-50' : 'text-slate-800 hover:bg-slate-50' }}">
                    Map Search
                </a>
                <a href="{{ route('contact') }}" class="px-2 xl:px-3 py-2 rounded-xl text-[12px] xl:text-[14px] font-bold {{ Route::is('contact') ? 'text-green-600 bg-green-50' : 'text-slate-800 hover:bg-slate-50' }}">Contact</a>

                <div class="h-6 w-[1px] bg-slate-200 mx-2"></div>

                @auth
                    @php
                        $user = auth()->user();
                        if ($user->hasRole('buyer'))
                            $dashRoute = route('buyer.dashboard');
                        elseif ($user->hasRole('seller'))
                            $dashRoute = route('seller.dashboard');
                        elseif ($user->hasRole('business'))
                            $dashRoute = route('business.dashboard');
                        elseif ($user->hasRole('ev-station'))
                            $dashRoute = route('station.dashboard');
                        elseif ($user->hasRole('garage'))
                            $dashRoute = route('garage.dashboard');
                        else
                            $dashRoute = route('dashboard');
                        $roleLabel = $user->hasRole('buyer') ? 'Buyer'
                            : ($user->hasRole('seller') ? 'Seller'
                                : ($user->hasRole('business') ? 'Business'
                                    : ($user->hasRole('ev-station') ? 'EV Station'
                                        : ($user->hasRole('garage') ? 'Garage' : 'User'))));
                        $unreadCount = $user->unreadNotificationCount();
                    @endphp

                    <!-- {{-- Dashboard quick-link --}}
                    <a href="{{ $dashRoute }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-[13px] font-bold text-green-700 bg-green-50 hover:bg-green-100 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a> -->

                    {{-- Avatar + dropdown --}}
                    <div class="relative" id="userMenuWrapper">
                        <button onclick="toggleUserMenu()"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-50 transition-all group">

                            {{-- Avatar with unread notification badge --}}
                            <div class="relative">
                                @if($user->profile_photo)
                                    <img src="{{ Storage::url($user->profile_photo) }}"
                                         alt="{{ $user->name }}"
                                         class="w-8 h-8 rounded-lg object-cover shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-slate-900 flex items-center justify-center text-white text-[11px] font-black uppercase tracking-wide shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                @if ($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center leading-none">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            <div class="text-left leading-tight">
                                <p class="text-[13px] font-bold text-slate-900 leading-none">{{ explode(' ', $user->name)[0] }}</p>
                                <p class="text-[10px] font-bold text-green-600 uppercase tracking-wide leading-none mt-0.5">{{ $roleLabel }}</p>
                            </div>
                            <svg id="userChevron" class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Dropdown panel --}}
                        <div id="userDropdown"
                            class="absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl shadow-[0_20px_40px_rgba(0,0,0,0.12)] border border-slate-100 py-2 invisible opacity-0 scale-95 transition-all duration-200 origin-top-right z-50">

                            {{-- User info --}}
                            <div class="px-4 py-3 border-b border-slate-100 flex items-center gap-3">
                                @if($user->profile_photo)
                                    <img src="{{ Storage::url($user->profile_photo) }}"
                                         alt="{{ $user->name }}"
                                         class="w-9 h-9 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-slate-900 flex items-center justify-center text-white text-xs font-black uppercase shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-[13px] font-bold text-slate-900 truncate">{{ $user->name }}</p>
                                    <p class="text-[11px] text-slate-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>

                            {{-- My Dashboard --}}
                            <a href="{{ $dashRoute }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:bg-slate-50 transition-all">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                My Dashboard
                            </a>

                            {{-- Profile Settings --}}
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:bg-slate-50 transition-all">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Profile Settings
                            </a>

                            {{-- My Bookings --}}
                            <a href="{{ route('booking.mine') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:bg-slate-50 transition-all">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                My Bookings
                            </a>

                            {{-- Notifications --}}
                            <a href="{{ route('notifications.index') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:bg-slate-50 transition-all">
                                <div class="relative w-4 h-4 shrink-0">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    @if ($unreadCount > 0)
                                        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                                    @endif
                                </div>
                                <span class="flex-1">Notifications</span>
                                @if ($unreadCount > 0)
                                    <span class="text-[10px] font-black bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full leading-none">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>

                            <div class="border-t border-slate-100 my-1"></div>

                            {{-- Sign Out --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-[13px] font-bold text-red-600 hover:bg-red-50 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>

                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center justify-center bg-[#4ade80] text-black px-5 py-2.5 rounded-xl text-[13px] font-black shadow-lg shadow-green-400/20 hover:bg-[#22c55e] transition-all active:scale-95">
                        Login / Sign Up
                    </a>
                @endauth
            </div>

            <button onclick="toggleMobileMenu()"
                class="lg:hidden w-10 h-10 flex items-center justify-center bg-slate-100 rounded-xl text-slate-900">
                <svg id="menuIcon" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition-transform"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </nav>

    {{-- Mobile Menu --}}
    <div id="mobileMenu" class="fixed inset-0 z-[-1] invisible opacity-0 transition-all duration-300">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="toggleMobileMenu()"></div>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[92%] bg-white rounded-[1.5rem] shadow-2xl border border-slate-100 transform scale-95 transition-transform duration-300 origin-top overflow-hidden" id="menuCard">
        <div class="overflow-y-auto max-h-[80vh] p-5">
            <div class="flex flex-col space-y-1">

                    @auth
                        @php
                            $user = auth()->user();
                            if ($user->hasRole('buyer'))
                                $dashRoute = route('buyer.dashboard');
                            elseif ($user->hasRole('seller'))
                                $dashRoute = route('seller.dashboard');
                            elseif ($user->hasRole('business'))
                                $dashRoute = route('business.dashboard');
                            elseif ($user->hasRole('ev-station'))
                                $dashRoute = route('station.dashboard');
                            elseif ($user->hasRole('garage'))
                                $dashRoute = route('garage.dashboard');
                            else
                                $dashRoute = route('dashboard');
                            $roleLabel = $user->hasRole('buyer') ? 'Buyer'
                                : ($user->hasRole('seller') ? 'Seller'
                                    : ($user->hasRole('business') ? 'Business'
                                        : ($user->hasRole('ev-station') ? 'EV Station'
                                            : ($user->hasRole('garage') ? 'Garage' : 'User'))));
                            $unreadCount = $unreadCount ?? $user->unreadNotificationCount();
                        @endphp

                        {{-- Mobile user card --}}
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl mb-2">
                            <div class="relative">
                                @if($user->profile_photo)
                                    <img src="{{ Storage::url($user->profile_photo) }}"
                                        alt="{{ $user->name }}"
                                        class="w-10 h-10 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-xl bg-slate-900 flex items-center justify-center text-white text-[12px] font-black uppercase shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                @endif
                                @if ($unreadCount > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center leading-none">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </div>
                            <div>
                                <p class="text-[14px] font-bold text-slate-900 leading-tight">{{ $user->name }}</p>
                                <p class="text-[11px] font-bold text-green-600 uppercase tracking-wide">{{ $roleLabel }}</p>
                            </div>
                        </div>

                        <a href="{{ $dashRoute }}"
                            class="flex items-center justify-between p-4 rounded-2xl bg-green-50 text-green-700 font-bold text-sm">
                            <span>My Dashboard</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endauth

                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 ml-4 mt-2">Explore BijuliCar</p>

                    <a href="{{ route('marketplace') }}"
                        class="flex items-center justify-between p-4 rounded-2xl {{ Route::is('marketplace') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-700' }}">
                        <span class="font-bold">Marketplace</span>
                        <span class="text-lg"><i class="fa-solid fa-cart-shopping" style="color: rgb(55, 225, 175);"></i></span>
                    </a>

                    <a href="{{ route('loan_calculator') }}"
                        class="flex items-center justify-between p-4 rounded-2xl {{ Route::is('loan_calculator') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-700' }}">
                        <span class="font-bold">Loan Calculator</span>
                        <span class="text-lg"><i class="fa-solid fa-wallet" style="color: rgb(55, 225, 175);"></i></span>
                    </a>

                    <a href="{{ route('map_location') }}"
                        class="flex items-center justify-between p-4 rounded-2xl {{ Route::is('map_location') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-700' }}">
                        <span class="font-bold">Map Search</span>
                        <span class="text-lg"><i class="fa-solid fa-map-location" style="color: rgb(55, 225, 175);"></i></span>
                    </a>

                    <a href="{{ route('news') }}"
                        class="flex items-center justify-between p-4 rounded-2xl {{ Route::is('news') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-700' }}">
                        <span class="font-bold">News & Updates</span>
                        <span class="text-lg"><i class="fa-solid fa-newspaper" style="color: rgb(55, 225, 175);"></i></span>
                    </a>

                    <a href="{{ route('businesses.index') }}"
                        class="flex items-center justify-between p-4 rounded-2xl {{ Route::is('businesses.*') ? 'bg-green-50 text-green-600' : 'bg-slate-50 text-slate-700' }}">
                        <span class="font-bold">Businesses</span>
                        <span class="text-lg"><i class="fa-solid fa-user-tie" style="color: rgb(55, 225, 175);"></i></span>
                    </a>

                    <div class="h-px bg-slate-100 my-2"></div>

                    @auth
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 text-slate-700 font-bold text-sm">
                            <span>Profile Settings</span>
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </a>

                        {{-- Mobile: My Bookings --}}
                        <a href="{{ route('booking.mine') }}"
                            class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 text-slate-700 font-bold text-sm">
                            <span>My Bookings</span>
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </a>

                        {{-- Mobile: Notifications --}}
                        <a href="{{ route('notifications.index') }}"
                            class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 text-slate-700 font-bold text-sm">
                            <span class="flex items-center gap-2">
                                Notifications
                                @if ($unreadCount > 0)
                                    <span class="text-[10px] font-black bg-red-100 text-red-600 px-1.5 py-0.5 rounded-full leading-none">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </span>
                            <svg class="w-6 h-6 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-between p-4 rounded-2xl bg-red-50 text-red-600 font-bold text-sm">
                                <span>Sign Out</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center justify-center py-4 rounded-2xl bg-[#4ade80] text-black font-black text-sm shadow-lg shadow-green-400/20">
                            Login / Sign Up
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        const card = document.getElementById('menuCard');
        const icon = document.getElementById('menuIcon');
        const body = document.body; // Reference the body

        if (menu.classList.contains('invisible')) {
            menu.classList.remove('invisible', 'opacity-0');
            card.classList.remove('scale-95');
            card.classList.add('scale-100');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';

            // Lock Scroll
            body.style.overflow = 'hidden';
        } else {
            menu.classList.add('opacity-0');
            card.classList.add('scale-95');
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';

            // Restore Scroll
            body.style.overflow = '';

            setTimeout(() => menu.classList.add('invisible'), 300);
        }
    }

    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        const chevron  = document.getElementById('userChevron');
        if (!dropdown) return;
        const isOpen = !dropdown.classList.contains('invisible');
        if (isOpen) {
            dropdown.classList.add('invisible', 'opacity-0', 'scale-95');
            chevron.style.transform = '';
        } else {
            dropdown.classList.remove('invisible', 'opacity-0', 'scale-95');
            chevron.style.transform = 'rotate(180deg)';
        }
    }

    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('userMenuWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const dropdown = document.getElementById('userDropdown');
            const chevron  = document.getElementById('userChevron');
            if (dropdown && !dropdown.classList.contains('invisible')) {
                dropdown.classList.add('invisible', 'opacity-0', 'scale-95');
                if (chevron) chevron.style.transform = '';
            }
        }
    });
</script>

