<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Business Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon" />

    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="bg-[#f1f5f9] h-screen w-screen overflow-hidden">
    <main class="flex h-full w-full">

        {{-- Left panel --}}
        <section class="hidden lg:block w-[50%] h-full relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1486325212027-8081e485255e?q=80&w=2070&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-40 grayscale-[0.2]" alt="Business">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-12 h-1 bg-purple-500 rounded-full"></span>
                    <p class="text-[10px] font-black text-[#a855f7] uppercase tracking-[0.4em]">Business Verification</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-6">
                    REGISTER YOUR <br>BUSINESS <span class="text-[#a855f7]">TODAY.</span>
                </h2>
                <ul class="space-y-4 text-slate-300 text-sm font-bold uppercase tracking-widest">
                    <li class="flex items-center gap-3"><span class="text-[#a855f7]">✔</span> Bulk listings enabled</li>
                    <li class="flex items-center gap-3"><span class="text-[#a855f7]">✔</span> Advertisement access</li>
                    <li class="flex items-center gap-3"><span class="text-[#a855f7]">✔</span> Business analytics dashboard</li>
                </ul>
            </div>
        </section>

        {{-- Right panel — form --}}
        <section class="w-full lg:w-[50%] h-full flex flex-col justify-center px-8 md:px-20 bg-white relative z-10 overflow-y-auto no-scrollbar">

            {{-- Progress bar --}}
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3 px-1">
                    <span class="text-[10px] font-black text-[#a855f7] uppercase tracking-widest">02. Verification</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Business</span>
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-full h-full bg-purple-500 shadow-[0_0_8px_rgba(37,99,235,0.4)]"></div>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Business <span class="text-[#a855f7]">Details</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-1">
                    Submit your business registration so our team can verify and activate your account.
                </p>
            </div>

            {{-- Alerts --}}
            @if (session('info'))
                <div class="mb-4 bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-[#a855f7]">{{ session('info') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-red-700 uppercase tracking-wider mb-1">Please fix the following:</p>
                    <ul class="space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li class="text-[11px] text-red-600 font-medium">• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('business.verify.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Business name --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}" required
                        placeholder="e.g. Kathmandu EV Motors Pvt. Ltd."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-purple-500 focus:bg-white transition-all font-medium @error('business_name') border-red-400 bg-red-50 @enderror">
                    @error('business_name')
                        <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact + Email --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" required
                            placeholder="01-XXXXXXX or 98XXXXXXXX"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-purple-500 focus:bg-white transition-all font-medium @error('contact') border-red-400 bg-red-50 @enderror">
                        @error('contact')
                            <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled
                            class="w-full bg-slate-100 border border-slate-200 rounded-xl py-3 px-4 text-sm text-slate-400 font-medium cursor-not-allowed">
                        <p class="text-[9px] text-slate-400 font-bold ml-1">From your account</p>
                    </div>
                </div>

                {{-- Registration document upload --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Business Registration Document <span class="text-slate-300">(JPG, PNG or PDF — max 4MB)</span>
                    </label>

                    <label class="block cursor-pointer group">
                        <input type="file" name="registration_doc" id="reg_doc_input" required accept=".jpg,.jpeg,.png,.pdf"
                            class="sr-only" onchange="updateFileName(this, 'reg_doc_label')">
                        <div id="reg_doc_label"
                            class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl py-6 px-4 text-center transition-all group-hover:border-purple-500 group-hover:bg-purple-50/20 @error('registration_doc') border-red-400 bg-red-50 @enderror">
                            <div class="text-2xl mb-1">🏢</div>
                            <p class="text-sm font-bold text-slate-500 group-hover:text-[#a855f7]">Click to upload Registration Document</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">PAN card, company registration certificate, or trade license</p>
                        </div>
                    </label>
                    @error('registration_doc')
                        <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notice --}}
                <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                    <p class="text-[11px] font-bold text-purple-700 uppercase tracking-wider mb-1">Document privacy</p>
                    <p class="text-xs text-[#a855f7]">Your registration documents are stored on a secure private server and are only accessible to our admin team during the verification process.</p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-purple-600 transition-all flex items-center justify-center gap-3 shadow-xl group">
                    Submit for Review
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                Wrong account? <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-[#a855f7] hover:underline ml-1">Sign Out</a>
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
                    <div class="text-2xl mb-1">✅</div>
                    <p class="text-sm font-bold text-[#a855f7]">${file.name}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">${sizeMB} MB — click to change</p>
                `;
                label.classList.add('border-purple-500', 'bg-purple-50/20');
                label.classList.remove('border-dashed');
            }
        }
    </script>
</body>
</html>