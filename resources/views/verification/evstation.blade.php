<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | EV Station Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
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
            <img src="https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2072&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-40 grayscale-[0.2]"
                alt="EV Charging Station">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-4"> {{-- Reduced mb --}}
                    <span class="w-12 h-1 bg-emerald-500 rounded-full"></span>
                    <p class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.4em]">Infrastructure
                        Verification</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-4"> {{-- Reduced mb --}}
                    POWER THE <br>FUTURE <span class="text-emerald-500">TODAY.</span>
                </h2>
                <ul class="space-y-3 text-slate-300 text-sm font-bold uppercase tracking-widest"> {{-- Reduced space-y --}}
                    <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Real-time map visibility
                    </li>
                    <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Station availability
                        tracking</li>
                    <li class="flex items-center gap-3"><span class="text-emerald-500">✔</span> Billing & Usage
                        analytics</li>
                </ul>
            </div>
        </section>

        {{-- Right panel — form --}}
        <section
            class="w-full lg:w-[50%] h-full flex flex-col justify-center px-8 md:px-20 bg-white relative z-10 overflow-y-auto no-scrollbar py-6"> {{-- Added py-6 for overall constraint --}}

            {{-- Progress bar --}}
            <div class="mb-4"> {{-- Reduced mb --}}
                <div class="flex justify-between items-center mb-2 px-1"> {{-- Reduced mb --}}
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">02. Node
                        Verification</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">EV Station</span>
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-full h-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></div>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-4"> {{-- Reduced mb --}}
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Station <span class="text-emerald-500">Credentials</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-0.5"> {{-- Reduced mt --}}
                    Verify your charging facility to appear on the global Bijulicar network.
                </p>
            </div>

            {{-- Alerts --}}
            @if (session('info'))
                <div class="mb-3 bg-emerald-50 border border-emerald-200 rounded-xl p-3"> {{-- Reduced mb and p --}}
                    <p class="text-xs font-bold text-emerald-700">{{ session('info') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-3 bg-red-50 border border-red-200 rounded-xl p-3"> {{-- Reduced mb and p --}}
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-0.5">Validation errors:</p>
                    <ul class="space-y-0">
                        @foreach ($errors->all() as $error)
                            <li class="text-[11px] text-red-600 font-medium">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('station.verify.store') }}" enctype="multipart/form-data"
                class="space-y-3"> {{-- Reduced space-y --}}
                @csrf

                {{-- Station Name --}}
                <div class="space-y-0.5"> {{-- Reduced space-y --}}
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Station
                        Name</label>
                    <input type="text" name="station_name" value="{{ old('station_name') }}" required
                        placeholder="e.g. Teku Fast Charge Hub"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white transition-all font-medium @error('station_name') border-red-400 bg-red-50 @enderror"> {{-- Reduced py --}}
                </div>

                {{-- Contact + Location --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3"> {{-- Reduced gap --}}
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact
                            Number</label>
                        <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                            placeholder="Primary contact"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white transition-all font-medium"> {{-- Reduced py --}}
                    </div>
                    <div class="space-y-0.5">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Physical
                            Address</label>
                        <input type="text" name="station_location" value="{{ old('station_location') }}" required
                            placeholder="Area/Street Name"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-2.5 px-4 text-sm focus:outline-none focus:border-emerald-500 focus:bg-white transition-all font-medium"> {{-- Reduced py --}}
                    </div>
                </div>

                {{-- License document upload --}}
                <div class="space-y-0.5">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Operating License / NEA Approval <span class="text-slate-300">(MAX 5MB)</span>
                    </label>

                    <label class="block cursor-pointer group">
                        <input type="file" name="operating_license" id="license_input" required
                            accept=".jpg,.jpeg,.png,.pdf" class="sr-only"
                            onchange="updateFileName(this, 'license_label')">
                        <div id="license_label"
                            class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl py-4 px-4 text-center transition-all group-hover:border-emerald-500 group-hover:bg-emerald-50/20"> {{-- Reduced py --}}
                            <div class="text-xl mb-0.5">⚡</div> {{-- Reduced size/mb --}}
                            <p class="text-sm font-bold text-slate-500 group-hover:text-emerald-600">Click to upload
                                Operating License</p>
                            <p class="text-[10px] text-slate-400 mt-0">Electrical inspection certificate</p> {{-- Shortened text --}}
                        </div>
                    </label>
                </div>

                {{-- Policy Notice --}}
                <div class="bg-slate-900 border border-slate-800 rounded-xl p-3"> {{-- Reduced p --}}
                    <p class="text-[11px] font-bold text-emerald-400 uppercase tracking-wider mb-0.5">Network Protocol</p>
                    <p class="text-xs text-slate-400">By submitting, you agree to provide real-time uptime status.</p> {{-- Shortened text --}}
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3.5 bg-emerald-600 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-emerald-500 transition-all flex items-center justify-center gap-3 shadow-xl group"> {{-- Reduced py --}}
                    Initialize Node Review
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </button>
            </form>

            <p class="mt-4 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest"> {{-- Reduced mt --}}
                Cancel Registration? <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-emerald-500 hover:underline ml-1">Sign Out</a>
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
                    <div class="text-xl mb-0.5">📡</div>
                    <p class="text-sm font-bold text-emerald-600">${file.name}</p>
                    <p class="text-[10px] text-slate-400 mt-0">${sizeMB} MB — Uplink ready</p>
                `;
                label.classList.add('border-emerald-500', 'bg-emerald-50/20');
                label.classList.remove('border-dashed');
            }
        }
    </script>
</body>

</html>