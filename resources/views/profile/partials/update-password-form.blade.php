<section class="max-w-2xl">
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-8 h-1 bg-[#16a34a] rounded-full"></span>
            <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-[0.3em]">
                {{ __('Security Protocol') }}
            </p>
        </div>
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
            {{ __('Update') }} <span class="text-[#16a34a]">{{ __('Password') }}</span>
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div class="space-y-1">
            <label for="update_password_current_password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Current Password') }}
            </label>
            <input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('current_password', 'updatePassword') border-red-500 @enderror" 
                autocomplete="current-password" 
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-[9px] font-bold uppercase" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-1">
                <label for="update_password_password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    {{ __('New Password') }}
                </label>
                <input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('password', 'updatePassword') border-red-500 @enderror" 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-[9px] font-bold uppercase" />
            </div>

            <div class="space-y-1">
                <label for="update_password_password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    {{ __('Confirm New Password') }}
                </label>
                <input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                    autocomplete="new-password" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-[9px] font-bold uppercase" />
            </div>
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all shadow-lg flex items-center gap-2 group">
                {{ __('Revise Credentials') }}
                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest italic"
                >
                    {{ __('✓ Password Secured') }}
                </p>
            @endif
        </div>
    </form>
</section>