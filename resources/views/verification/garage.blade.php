<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Garage Network Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>

<body class="bg-[#f1f5f9] h-screen w-screen overflow-hidden">
    <main class="flex h-full w-full">

        {{-- Left panel --}}
        <section class="hidden lg:block w-[50%] h-full relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1507702553912-a15641ec5821?q=80&w=2070&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-40 grayscale-[0.3]"
                alt="Modern Auto Workshop">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-12 h-1 bg-amber-500 rounded-full"></span>
                    <p class="text-[10px] font-black text-amber-400 uppercase tracking-[0.4em]">Service Provider
                        Verification</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-4">
                    PRECISION <br>REPAIR <span class="text-amber-500">EXPERT.</span>
                </h2>
                <ul class="space-y-3 text-slate-300 text-sm font-bold uppercase tracking-widest">
                    <li class="flex items-center gap-3"><span class="text-amber-500">✔</span> Priority Service Bookings</li>
                    <li class="flex items-center gap-3"><span class="text-amber-500">✔</span> Expert Badge Visibility</li>
                    <li class="flex items-center gap-3"><span class="text-amber-500">✔</span> Fleet Maintenance Access</li>
                </ul>
            </div>
        </section>

        {{-- Right panel — form --}}
        <section
            class="w-full lg:w-[50%] h-full flex flex-col justify-center px-8 md:px-20 bg-white relative z-10 overflow-y-auto no-scrollbar">

            {{-- Progress bar --}}
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2 px-1">
                    <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">02. Workshop
                        Credentials</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Garage Partner</span>
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-full h-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]"></div>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-4">
                <h1 class="text-2xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Workshop <span class="text-amber-500">Authentication</span>
                </h1>
                <p class="text-slate-500 text-xs font-medium mt-0.5">
                    List your professional garage to provide maintenance services for the EV community.
                </p>
            </div>

            {{-- Alerts --}}
            @if (session('info'))
                <div class="mb-3 bg-amber-50 border border-amber-200 rounded-xl p-3 text-[11px] font-bold text-amber-700">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-3 bg-red-50 border border-red-200 rounded-xl p-3">
                    <ul class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-[10px] text-red-600 font-medium">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('garage.verify.store') }}" enctype="multipart/form-data"
                class="space-y-3">
                @csrf

                {{-- Garage Name --}}
                <div class="space-y-0.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Garage Name</label>
                    <input type="text" name="garage_name" value="{{ old('garage_name') }}" required
                        placeholder="e.g. Kathmandu Precision Motors"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-medium @error('garage_name') border-red-400 bg-red-50 @enderror">
                </div>

                {{-- Contact + Specialization --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="space-y-0.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" required
                            placeholder="98XXXXXXXX"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-medium">
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Specialization</label>
                        <input type="text" name="specialization" value="{{ old('specialization') }}" required
                            placeholder="EV Repair, Battery"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-medium">
                    </div>
                </div>

                {{-- Physical Address --}}
                <div class="space-y-0.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Physical Address</label>
                    <input type="text" name="garage_location" value="{{ old('garage_location') }}" required
                        placeholder="Full street address with city"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-amber-500 focus:bg-white transition-all font-medium">
                </div>

                {{-- Business License upload --}}
                <div class="space-y-0.5">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Business License / Registration <span class="text-slate-300">(MAX 5MB)</span>
                    </label>

                    <label class="block cursor-pointer group">
                        <input type="file" name="license" id="license_input" required accept=".jpg,.jpeg,.png,.pdf"
                            class="sr-only" onchange="updateFileName(this, 'license_label')">
                        <div id="license_label"
                            class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl py-4 px-4 text-center transition-all group-hover:border-amber-500 group-hover:bg-amber-50/20">
                            <div class="text-xl mb-0.5">🔧</div>
                            <p class="text-xs font-bold text-slate-500 group-hover:text-amber-600">Click to upload Business License</p>
                        </div>
                    </label>
                </div>

                {{-- Policy Notice --}}
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-3">
                    <p class="text-[10px] font-bold text-amber-400 uppercase tracking-wider mb-0.5">Service Standards</p>
                    <p class="text-[11px] text-slate-400">By joining the network, you commit to quality EV maintenance standards.</p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-amber-600 text-white rounded-xl font-black uppercase italic tracking-widest text-[11px] hover:bg-amber-500 transition-all flex items-center justify-center gap-2 shadow-lg group">
                    Submit for Review
                    <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="mt-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Wrong Role? <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-amber-600 hover:underline ml-1">Sign Out</a>
            </p>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </section>
    </main>

    <script>
        function updateFileName(input, labelId) {
            const label = document.getElementById(labelId);
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                label.innerHTML = `
                    <div class="text-xl mb-0.5">📦</div>
                    <p class="text-xs font-bold text-amber-600">${file.name}</p>
                    <p class="text-[9px] text-slate-400">${sizeMB} MB — Ready</p>
                `;
                label.classList.add('border-amber-500', 'bg-amber-50/20');
                label.classList.remove('border-dashed');
            }
        }
    </script>
</body>

</html>