<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Verify Your Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        @keyframes pulse-ring {
            0%   { transform: scale(0.9); opacity: 0.6; }
            50%  { transform: scale(1.05); opacity: 0.2; }
            100% { transform: scale(0.9); opacity: 0.6; }
        }
        .pulse-ring { animation: pulse-ring 2.5s ease-in-out infinite; }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-6px); }
        }
        .float { animation: float 3s ease-in-out infinite; }

        /* ── BUG 1 FIX: spinner shown while polling detects verification ── */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .spin { animation: spin 1s linear infinite; }
    </style>
</head>

<body class="bg-[#f1f5f9] min-h-screen flex items-center justify-center p-4 md:p-6">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2 mb-8 md:mb-10">
            <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-slate-900 font-black tracking-tighter uppercase text-lg">Bijuli<span class="text-[#16a34a]">Car</span></span>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl md:rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Top accent bar --}}
            <div class="h-1.5 w-full bg-blue-500"></div>

            <div class="p-6 md:p-10 text-center">

                {{-- Animated icon --}}
                <div class="relative flex items-center justify-center mb-6 md:mb-8">
                    <div class="pulse-ring absolute w-20 h-20 md:w-24 md:h-24 rounded-full bg-blue-100"></div>
                    <div class="float relative w-14 h-14 md:w-16 md:h-16 bg-blue-50 border-2 border-blue-200 rounded-2xl flex items-center justify-center">
                        <svg class="w-6 h-6 md:w-8 md:h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                {{-- Step indicator --}}
                <div class="flex items-center justify-center gap-1 md:gap-2 mb-6">
                    <div class="flex items-center gap-1 md:gap-1.5">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-blue-500 flex items-center justify-center shrink-0">
                            <span class="text-[9px] md:text-[10px] font-black text-white">1</span>
                        </div>
                        <span class="text-[8px] md:text-[10px] font-black text-blue-500 uppercase tracking-wider">Verify</span>
                    </div>
                    <div class="w-4 md:w-8 h-px bg-slate-200"></div>
                    <div class="flex items-center gap-1 md:gap-1.5">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                            <span class="text-[9px] md:text-[10px] font-black text-slate-400">2</span>
                        </div>
                        <span class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-wider">Docs</span>
                    </div>
                    <div class="w-4 md:w-8 h-px bg-slate-200"></div>
                    <div class="flex items-center gap-1 md:gap-1.5">
                        <div class="w-5 h-5 md:w-6 md:h-6 rounded-full bg-slate-200 flex items-center justify-center shrink-0">
                            <span class="text-[9px] md:text-[10px] font-black text-slate-400">3</span>
                        </div>
                        <span class="text-[8px] md:text-[10px] font-black text-slate-400 uppercase tracking-wider">Approved</span>
                    </div>
                </div>

                <p class="text-[9px] md:text-[10px] font-black text-blue-500 uppercase tracking-[0.3em] mb-2">Step 1 of 3</p>
                <h1 class="text-xl md:text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                    Check Your <span class="text-blue-500">Inbox</span>
                </h1>
                <p class="text-slate-500 text-xs md:text-sm font-medium leading-relaxed max-w-sm mx-auto">
                    We sent a verification link to
                    <span class="font-black text-slate-900 block sm:inline">{{ auth()->user()->email }}</span>.
                    Click the link to continue setting up your account.
                </p>

                {{-- Success flash --}}
                @if (session('status') == 'verification-link-sent')
                    <div class="mt-5 flex items-start md:items-center gap-2 text-left md:text-center justify-center bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5 md:mt-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs md:text-sm font-bold text-green-700">A fresh verification link has been sent.</span>
                    </div>
                @endif

                {{-- ── BUG 1 FIX: auto-detected banner ─────────────────────────────
                     Shown by JS once polling discovers the email has been verified.
                     Hidden by default (hidden class). JS removes 'hidden' and then
                     redirects after a short delay so the user sees the message.
                ──────────────────────────────────────────────────────────────────── --}}
                <div id="verified-banner" class="hidden mt-5 flex items-start md:items-center gap-2 text-left md:text-center justify-center bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                    <svg class="w-4 h-4 text-green-500 shrink-0 mt-0.5 md:mt-0 spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-xs md:text-sm font-bold text-green-700">
                        Email verified! Taking you to the next step…
                    </span>
                </div>

                {{-- What to expect box --}}
                <div class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-4 md:p-5 text-left space-y-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">What happens next</p>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[9px] font-black text-blue-600">1</span>
                        </div>
                        <p class="text-xs font-medium text-slate-600">Open the email from <span class="font-bold text-slate-900">BijuliCar</span> and click the verify button.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[9px] font-black text-slate-500">2</span>
                        </div>
                        <p class="text-xs font-medium text-slate-600">You'll be taken to the <span class="font-bold text-slate-900">document verification form</span> to submit your details.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center shrink-0 mt-0.5">
                            <span class="text-[9px] font-black text-slate-500">3</span>
                        </div>
                        <p class="text-xs font-medium text-slate-600">Our admin team reviews and <span class="font-bold text-slate-900">approves your account</span> — usually within 24 hours.</p>
                    </div>
                </div>

                {{-- Resend button --}}
                <div class="mt-6">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit"
                            class="w-full py-3.5 md:py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-[10px] md:text-xs hover:bg-blue-600 transition-all flex items-center justify-center gap-3 group">
                            Resend Verification Email
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                {{-- ── BUG 1 FIX: "already verified" escape hatch ────────────────────
                     If the user verified in another browser or tab they can click this
                     link rather than waiting for the poll. It hits /verify-email (GET)
                     which is EmailVerificationPromptController — that controller already
                     checks hasVerifiedEmail() and forwards verified users to the doc form.
                     This is a zero-cost safety net on top of the JS polling below.
                ──────────────────────────────────────────────────────────────────── --}}
                <p class="text-[10px] md:text-[11px] text-slate-400 font-medium mt-4">
                    Didn't get it? Check your spam folder or resend above.
                </p>
                <p class="text-[10px] md:text-[11px] text-slate-400 font-medium mt-2">
                    Already clicked the link in another browser?
                    <a href="{{ route('verification.notice') }}"
                       class="text-blue-500 font-bold underline hover:text-blue-700 transition-colors">
                        Continue here
                    </a>
                </p>

            </div>
        </div>

        {{-- Sign out --}}
        <div class="text-center mt-6">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                    Sign out of {{ auth()->user()->email }}
                </button>
            </form>
        </div>

    </div>

    {{-- ── BUG 1 FIX: Cross-browser polling ────────────────────────────────────
         Problem: When the user verifies their email in Browser B, the database
         email_verified_at column is updated, but Browser A is just sitting on
         this static page. It never makes another server request, so it never
         discovers the verification happened. The user is stuck indefinitely.

         Solution: Poll the /check-email-verified endpoint every 4 seconds.
         That endpoint (added below) is a tiny JSON route that returns
         {"verified": true/false} by re-querying the DB for the current user.
         As soon as we get {"verified": true} we show a confirmation banner
         and redirect to /verify-email (GET) which is EmailVerificationPromptController —
         that controller already handles forwarding verified users to the doc form.

         Why not poll /verify-email directly?
         /verify-email (GET) returns a full HTML page redirect, not JSON.
         Following redirects with fetch() in the same origin would land us
         on the doc-form page, but we can't detect that cleanly across
         all browsers. A dedicated JSON endpoint is simpler and more reliable.

         Why 4 seconds?
         Fast enough that the user barely notices the delay; slow enough to
         not hammer the server. With throttle:20,1 on the route it is safe.
    ──────────────────────────────────────────────────────────────────────── --}}
    <script>
        (function () {
            var banner   = document.getElementById('verified-banner');
            var interval = null;
            var stopped  = false;

            function checkVerified() {
                if (stopped) return;

                fetch('{{ route('verification.check') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') 
                                        ? document.querySelector('meta[name="csrf-token"]').content 
                                        : ''
                    },
                    credentials: 'same-origin'
                })
                .then(function (res) {
                    if (!res.ok) return null;
                    return res.json();
                })
                .then(function (data) {
                    if (!data) return;
                    if (data.verified === true) {
                        stopped = true;
                        clearInterval(interval);

                        // Show the "Email verified!" banner
                        banner.classList.remove('hidden');
                        banner.classList.add('flex');

                        // Redirect after 1.5 s so the user sees the banner
                        setTimeout(function () {
                            window.location.href = '{{ route('verification.notice') }}';
                        }, 1500);
                    }
                })
                .catch(function () {
                    // Network error — silently ignore, keep polling
                });
            }

            // Start polling immediately, then every 4 seconds
            checkVerified();
            interval = setInterval(checkVerified, 4000);

            // Stop polling if the tab becomes hidden (saves requests)
            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    clearInterval(interval);
                } else {
                    // Tab became visible again — check immediately then resume
                    checkVerified();
                    interval = setInterval(checkVerified, 4000);
                }
            });
        })();
    </script>

</body>
</html>