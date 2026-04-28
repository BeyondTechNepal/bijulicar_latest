<nav x-data="{ open: false }" class="bg-white border-b-2 border-slate-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 no-underline group">
                        {{-- Center: logo --}}
                        <div class="flex items-center shrink-0 px-2 xl:px-4">
                            <a href="{{ route('home') }}" class="flex items-center gap-2 no-underline group">
                                <img src="{{ asset('images/logo.png') }}" alt="BijuliCar Logo"
                                    class="h-12 md:h-14 lg:h-16 w-auto object-contain transition-all duration-500 group-hover:scale-110 drop-shadow-sm">
                            </a>
                        </div>
                    </a>
                </div>

                <div class="hidden space-x-4 sm:-my-px sm:ms-12 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-[11px] font-black uppercase tracking-[0.2em] transition-all hover:text-[#16a34a]">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-4 py-2 border-2 border-slate-100 rounded-xl text-[11px] font-black uppercase tracking-widest text-slate-600 bg-slate-50 hover:bg-white hover:border-[#16a34a] hover:text-slate-900 focus:outline-none transition-all duration-200">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-[#16a34a] animate-pulse"></span>
                                {{ Auth::user()->name }}
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 bg-slate-900 border-b border-slate-800">
                            <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest">
                                {{ __('Active Session') }}</p>
                            <p class="text-xs font-bold text-white truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')"
                            class="text-[11px] font-bold uppercase tracking-wider hover:bg-slate-50">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                class="text-[11px] font-bold uppercase tracking-wider text-red-600 hover:bg-red-50"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-900 bg-slate-100 hover:bg-[#16a34a] hover:text-white transition-all">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden border-t-2 border-slate-100 bg-white">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="font-black uppercase italic tracking-widest text-xs">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-slate-100 bg-slate-50">
            <div class="px-4 mb-3">
                <div class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest">
                    {{ __('Authorized User') }}</div>
                <div class="font-black text-slate-900 uppercase italic">{{ Auth::user()->name }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="font-bold uppercase tracking-wider text-xs">
                    {{ __('Profile Settings') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        class="font-bold uppercase tracking-wider text-xs text-red-600"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Logout') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
