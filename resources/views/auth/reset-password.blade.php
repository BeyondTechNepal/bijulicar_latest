<x-guest-layout>
    <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-1">New <span class="text-[#16a34a]">Password</span></h2>
    <p class="text-slate-500 text-sm font-medium mb-6">Choose a strong password for your account.</p>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('email') border-red-500 @enderror">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-[11px] text-red-600 font-bold uppercase italic tracking-wider" />
        </div>

        <div class="space-y-1">
            <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">New Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('password') border-red-500 @enderror">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-[11px] text-red-600 font-bold uppercase italic tracking-wider" />
        </div>

        <div class="space-y-1">
            <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-[11px] text-red-600 font-bold uppercase italic tracking-wider" />
        </div>

        <button type="submit"
            class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-xl shadow-slate-200 group">
            Reset Password
            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>
</x-guest-layout>