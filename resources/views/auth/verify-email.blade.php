<x-guest-layout>
    <h2 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter mb-1">Verify <span class="text-[#16a34a]">Email</span></h2>
    <p class="text-slate-500 text-sm font-medium mb-6">Thanks for joining! Please verify your email address by clicking the link we just sent you.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 text-sm font-bold text-green-600 bg-green-50 border border-green-200 px-4 py-3 rounded-xl">
            A new verification link has been sent to your email address.
        </div>
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
                class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-xl shadow-slate-200 group">
                Resend Verification Email
                <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-3 text-[11px] font-black text-slate-400 hover:text-red-500 uppercase tracking-widest transition-colors">
                Sign Out
            </button>
        </form>
    </div>
</x-guest-layout>