<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Awaiting Approval</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.9);
                opacity: 0.6;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.2;
            }

            100% {
                transform: scale(0.9);
                opacity: 0.6;
            }
        }

        .pulse-ring {
            animation: pulse-ring 2.5s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-lg">

        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2 mb-10">
            <div class="w-9 h-9 bg-slate-900 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
            <span class="text-slate-900 font-black tracking-tighter uppercase text-lg">Bijuli<span
                    class="text-[#16a34a]">Car</span></span>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">

            {{-- Status indicator top bar --}}
            @php
                $user = auth()->user();
    $verification = match (true) {
        $user->hasRole('buyer') => $user->buyerVerification,
        $user->hasRole('seller') => $user->sellerVerification,
        $user->hasRole('business') => $user->businessVerification,
        $user->hasRole('ev-station') => $user->stationVerification,
        $user->hasRole('garage') => $user->garageVerification,
        default => null,
    };
    $status = $verification?->status ?? 'not_submitted';
            @endphp

            @if ($status === 'pending')
                <div class="h-1.5 w-full bg-amber-400"></div>
            @elseif ($status === 'rejected')
                <div class="h-1.5 w-full bg-red-500"></div>
            @else
                <div class="h-1.5 w-full bg-slate-200"></div>
            @endif

            <div class="p-10 text-center">

                {{-- Icon --}}
                <div class="relative flex items-center justify-center mb-8">
                    @if ($status === 'pending')
                        <div class="pulse-ring absolute w-24 h-24 rounded-full bg-amber-100"></div>
                        <div
                            class="relative w-16 h-16 bg-amber-50 border-2 border-amber-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @elseif ($status === 'rejected')
                        <div
                            class="relative w-16 h-16 bg-red-50 border-2 border-red-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    @else
                        <div
                            class="relative w-16 h-16 bg-slate-50 border-2 border-slate-200 rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Title & message --}}
                @if ($status === 'pending')
                    <p class="text-[10px] font-black text-amber-500 uppercase tracking-[0.3em] mb-2">Under Review</p>
                    <h1 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                        Verification <span class="text-amber-500">Pending</span>
                    </h1>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-sm mx-auto">
                        Your details have been submitted successfully. Our admin team is reviewing your information and
                        documents. You'll receive an email once a decision is made.
                    </p>

                    {{-- Details summary --}}
                    <div class="mt-6 bg-slate-50 border border-slate-200 rounded-2xl p-5 text-left space-y-3">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Submitted details</p>
                        @if ($user->hasRole('buyer') && $user->buyerVerification)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Full name</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->buyerVerification->full_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Contact</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->buyerVerification->contact }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">National ID</span>
                                <span class="text-xs font-bold text-[#16a34a]">Uploaded ✔</span>
                            </div>
                        @elseif ($user->hasRole('seller') && $user->sellerVerification)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Full name</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->sellerVerification->full_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Contact</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->sellerVerification->contact }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">National ID</span>
                                <span class="text-xs font-bold text-[#16a34a]">Uploaded ✔</span>
                            </div>
                        @elseif ($user->hasRole('business') && $user->businessVerification)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Business name</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->businessVerification->business_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Contact</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->businessVerification->contact }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Registration doc</span>
                                <span class="text-xs font-bold text-blue-600">Uploaded ✔</span>
                            </div>
                        @elseif ($user->hasRole('ev-station') && $user->stationVerification)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Station Name</span>
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $user->stationVerification->station_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Location</span>
                                <span
                                    class="text-xs font-bold text-slate-900 truncate ml-4">{{ $user->stationVerification->location_details }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">License Doc</span>
                                <span class="text-xs font-bold text-cyan-600">Uploaded ✔</span>
                            </div>
                        @elseif ($user->hasRole('garage') && $user->garageVerification)
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Garage Name</span>
                                <span class="text-xs font-bold text-slate-900">{{ $user->garageVerification->garage_name }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Location</span>
                                <span class="text-xs font-bold text-slate-900 truncate ml-4">{{ $user->garageVerification->garage_location }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Specialization</span>
                                <span class="text-xs font-bold text-slate-900">{{ $user->garageVerification->specialization }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-500">Workshop Doc</span>
                                <span class="text-xs font-bold text-amber-600">Uploaded ✔</span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between border-t border-slate-200 pt-3">
                            <span class="text-xs font-bold text-slate-500">Submitted at</span>
                            <span
                                class="text-xs font-bold text-slate-900">{{ $verification->created_at->format('M d, Y — h:i A') }}</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 font-medium mt-5">
                        Typical review time: <span class="font-bold text-slate-600">within 24 hours</span>
                    </p>
                @elseif ($status === 'rejected')
                    <p class="text-[10px] font-black text-red-500 uppercase tracking-[0.3em] mb-2">Not Approved</p>
                    <h1 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                        Application <span class="text-red-500">Rejected</span>
                    </h1>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-sm mx-auto">
                        Unfortunately your verification application was not approved. Please review the reason below,
                        fix the issue, and resubmit.
                    </p>

                    {{-- Rejection reason --}}
                    @if ($verification->rejection_reason)
                        <div class="mt-6 bg-red-50 border border-red-200 rounded-2xl p-5 text-left">
                            <p class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-2">Reason for
                                rejection</p>
                            <p class="text-sm text-red-700 font-medium leading-relaxed">
                                {{ $verification->rejection_reason }}</p>
                        </div>
                    @endif

                    {{-- Re-apply button --}}
                    <div class="mt-6">
                        @php
                            // This ensures every role goes to their specific form
                            $resubmitRoute = match (true) {
                                $user->hasRole('buyer') => route('buyer.verify.create'),
                                $user->hasRole('seller') => route('seller.verify.create'),
                                $user->hasRole('business') => route('business.verify.create'),
                                $user->hasRole('ev-station') => route('station.verify.create'),
                                $user->hasRole('garage') => route('garage.verify.create'),
                                default => route('dashboard'),
                            };
                        @endphp

                        <a href="{{ $resubmitRoute }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-red-600 transition-all shadow-lg group">
                            <span>Resubmit Application</span>
                            <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </a>
                    </div>
                @elseif ($status === 'approved')
    {{-- Approved users are redirected by controller. Blade just shows a green bar --}}
    <div class="h-1.5 w-full bg-green-500"></div>
    <p class="text-slate-500 text-sm mt-3">Your account is approved. Redirecting to dashboard...</p>

                @else
                    {{-- Not submitted yet — shouldn't normally land here, but just in case --}}
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-2">Action Required
                    </p>
                    <h1 class="text-2xl font-black text-slate-900 uppercase italic tracking-tight mb-3">
                        Complete Your <span class="text-[#16a34a]">Verification</span>
                    </h1>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-sm mx-auto mb-6">
                        You haven't submitted your verification details yet. Complete the form to get your account
                        approved.
                    </p>
                    @if ($user->hasRole('buyer'))
                        <a href="{{ route('buyer.verify.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#16a34a] text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-slate-900 transition-all shadow-lg">
                            Start Verification
                        </a>
                    @elseif ($user->hasRole('seller'))
                        <a href="{{ route('seller.verify.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-[#16a34a] text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-slate-900 transition-all shadow-lg">
                            Start Verification
                        </a>
                    @else
                        <a href="{{ route('business.verify.create') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-slate-900 transition-all shadow-lg">
                            Start Verification
                        </a>
                    @endif
                @endif

            </div>
        </div>

        {{-- Sign out link --}}
        <div class="text-center mt-6">
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="text-[11px] font-bold text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                    Sign out of {{ auth()->user()->email }}
                </button>
            </form>
        </div>

    </div>
</body>

</html>