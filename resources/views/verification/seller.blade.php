<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bijulicar | Seller Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon" />

    <style>
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="bg-[#f1f5f9] h-screen w-screen overflow-hidden">
    <main class="flex h-full w-full">

        {{-- Left panel --}}
        <section class="hidden lg:block w-[50%] h-full relative bg-slate-900">
            <img src="https://images.unsplash.com/photo-1593941707882-a5bba14938c7?q=80&w=2072&auto=format&fit=crop"
                class="absolute inset-0 w-full h-full object-cover opacity-50 grayscale-[0.2]" alt="EV">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-transparent to-transparent"></div>

            <div class="absolute top-1/2 left-16 -translate-y-1/2">
                <div class="flex items-center gap-2 mb-6">
                    <span class="w-12 h-1 bg-[#4ade80] rounded-full"></span>
                    <p class="text-[10px] font-black text-[#4ade80] uppercase tracking-[0.4em]">Seller Verification</p>
                </div>
                <h2 class="text-6xl font-black text-white uppercase italic tracking-tighter leading-[0.9] mb-6">
                    PROVE YOUR <br>IDENTITY <span class="text-[#4ade80]">ONCE.</span>
                </h2>
                <ul class="space-y-4 text-slate-300 text-sm font-bold uppercase tracking-widest">
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> One-time verification</li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Documents stored securely</li>
                    <li class="flex items-center gap-3"><span class="text-[#4ade80]">✔</span> Approved within 24 hours</li>
                </ul>
            </div>
        </section>

        {{-- Right panel — form --}}
        <section class="w-full lg:w-[50%] h-full flex flex-col justify-center px-8 md:px-20 bg-white relative z-10 overflow-y-auto no-scrollbar">

            {{-- Progress bar --}}
            <div class="mb-8">
                <div class="flex justify-between items-center mb-3 px-1">
                    <span class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest">02. Verification</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Seller</span>
                </div>
                <div class="w-full h-1 bg-slate-100 rounded-full">
                    <div class="w-full h-full bg-[#16a34a] shadow-[0_0_8px_rgba(22,163,74,0.4)]"></div>
                </div>
            </div>

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
                    Seller <span class="text-[#16a34a]">Details</span>
                </h1>
                <p class="text-slate-500 text-sm font-medium mt-1">
                    Submit your details so our team can verify your identity before you start listing.
                </p>
            </div>

            {{-- Session info/error alerts --}}
            @if (session('info'))
                <div class="mb-4 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <p class="text-xs font-bold text-blue-700">{{ session('info') }}</p>
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
            <form method="POST" action="{{ route('seller.verify.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                {{-- Full name --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" required
                        placeholder="As per your National ID"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('full_name') border-red-400 bg-red-50 @enderror">
                    @error('full_name')
                        <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact + Email side by side --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Contact Number</label>
                        <input type="text" name="contact" value="{{ old('contact') }}" required
                            placeholder="98XXXXXXXX"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('contact') border-red-400 bg-red-50 @enderror">
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

                {{-- National ID upload --}}
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        National ID <span class="text-slate-300">(JPG, PNG or PDF — max 4MB)</span>
                    </label>

                    <label class="block cursor-pointer group">
                        <input type="file" name="national_id" id="national_id_input" required accept=".jpg,.jpeg,.png,.pdf"
                            class="sr-only" onchange="updateFileName(this, 'national_id_label')">
                        <div id="national_id_label"
                            class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-xl py-6 px-4 text-center transition-all group-hover:border-[#16a34a] group-hover:bg-green-50/30 @error('national_id') border-red-400 bg-red-50 @enderror">
                            <div class="text-2xl mb-1">🪪</div>
                            <p class="text-sm font-bold text-slate-500 group-hover:text-[#16a34a]">Click to upload National ID</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">Front side clearly visible</p>
                        </div>
                    </label>
                    @error('national_id')
                        <p class="text-[9px] text-red-500 font-bold uppercase mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Notice --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <p class="text-[11px] font-bold text-amber-700 uppercase tracking-wider mb-1">Before you submit</p>
                    <p class="text-xs text-amber-600">Your documents are stored securely and only viewed by our admin team for verification purposes. They will never be shared publicly.</p>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-4 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all flex items-center justify-center gap-3 shadow-xl group">
                    Submit for Review
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

            <p class="mt-6 text-center text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                Wrong account? <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-[#16a34a] hover:underline ml-1">Sign Out</a>
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
                    <p class="text-sm font-bold text-[#16a34a]">${file.name}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">${sizeMB} MB — click to change</p>
                `;
                label.classList.add('border-[#16a34a]', 'bg-green-50/30');
                label.classList.remove('border-dashed');
            }
        }
    </script>
</body>
</html>