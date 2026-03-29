<section class="max-w-2xl space-y-6">
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-8 h-1 bg-red-500 rounded-full"></span>
            <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em]">
                {{ __('Terminal Action') }}
            </p>
        </div>
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
            {{ __('Terminate') }} <span class="text-red-500">{{ __('Account') }}</span>
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __('Once your account is deleted, all of its resources and data will be permanently purged from our systems.') }}
        </p>
    </header>

    <button 
        x-data="" 
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-8 py-3 bg-white border-2 border-red-500 text-red-600 rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-red-500 hover:text-white transition-all shadow-md group"
    >
        {{ __('Deactivate Identity') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">
                {{ __('Final') }} <span class="text-red-500">{{ __('Confirmation') }}</span>
            </h2>

            <p class="mt-3 text-sm font-medium text-slate-500 leading-relaxed">
                {{ __('This action is irreversible. Please enter your password to authorize the permanent deletion of your account and all associated data.') }}
            </p>

            <div class="mt-6 space-y-1">
                <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                    {{ __('Authorize with Password') }}
                </label>
                <input 
                    id="password" 
                    name="password" 
                    type="password" 
                    class="w-full md:w-3/4 bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-500 focus:bg-white transition-all font-medium @error('password', 'userDeletion') border-red-500 @enderror" 
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-[9px] font-bold uppercase text-red-500" />
            </div>

            <div class="mt-8 flex flex-col md:flex-row gap-3">
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-slate-200 transition-all"
                >
                    {{ __('Abort Mission') }}
                </button>

                <button 
                    type="submit"
                    class="px-8 py-3 bg-red-600 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-red-700 transition-all shadow-xl flex items-center justify-center gap-2 group"
                >
                    {{ __('Confirm Deletion') }}
                    <svg class="w-4 h-4 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </form>
    </x-modal>
</section>