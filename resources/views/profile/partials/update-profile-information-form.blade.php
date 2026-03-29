<section class="max-w-2xl">
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-8 h-1 bg-[#16a34a] rounded-full"></span>
            <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-[0.3em]">
                {{ __('Account Settings') }}
            </p>
        </div>
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
            {{ __('Profile') }} <span class="text-[#16a34a]">{{ __('Information') }}</span>
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="space-y-1">
            <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Full Name') }}
            </label>
            <input 
                id="name" 
                name="name" 
                type="text" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('name') border-red-500 @enderror" 
                value="{{ old('name', $user->name) }}" 
                required 
                autofocus 
                autocomplete="name" 
            />
            <x-input-error class="mt-2 text-[9px] font-bold uppercase" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Email Address') }}
            </label>
            <input 
                id="email" 
                name="email" 
                type="email" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('email') border-red-500 @enderror" 
                value="{{ old('email', $user->email) }}" 
                required 
                autocomplete="username" 
            />
            <x-input-error class="mt-2 text-[9px] font-bold uppercase" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl mt-4">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-tight">
                        {{ __('Your email address is unverified.') }}
                    </p>

                    <button form="send-verification" class="mt-2 text-[10px] font-black text-amber-600 uppercase tracking-widest hover:text-amber-700 underline underline-offset-4">
                        {{ __('Re-send Verification Email') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-black text-[10px] text-green-600 uppercase italic">
                            {{ __('A new link has been dispatched to your inbox.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all shadow-lg flex items-center gap-2 group">
                {{ __('Update Identity') }}
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest italic"
                >
                    {{ __('✓ Changes Synced') }}
                </p>
            @endif
        </div>
    </form>
</section>