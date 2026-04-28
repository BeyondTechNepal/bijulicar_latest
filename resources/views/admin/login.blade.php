<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Bijulicar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="min-h-screen bg-[#0a0c10] flex items-center justify-center px-4 relative overflow-hidden">

    <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-600/10 blur-[120px] rounded-full"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-cyan-500/10 blur-[120px] rounded-full"></div>

    <div class="w-full max-w-md z-10">

        <div class="text-center mb-8">
            <div
                class="inline-flex items-center gap-2 bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] px-3 py-1 rounded-full font-bold uppercase tracking-[0.2em] mb-4">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                Secure Staff Portal
            </div>
            <h1 class="text-3xl font-bold text-white tracking-tight">Bijuli<span class="text-blue-500">car</span></h1>
            <p class="text-slate-500 text-sm mt-1 font-light">Centralized Administration System</p>
        </div>

        <div class="glass rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden">

            <div class="px-8 py-3 bg-white/5 border-b border-white/5 flex justify-between items-center">
                <div class="font-mono text-[10px] uppercase tracking-tighter text-slate-500">
                    <span class="text-blue-400">auth</span>.guard: <span class="text-slate-300">admin</span>
                </div>
                <div class="h-1 w-12 bg-blue-500/30 rounded-full"></div>
            </div>

            <form method="POST" action="{{ route('admin.login') }}" class="px-8 py-8 space-y-5">
                @csrf

                @if ($errors->any())
                    <div
                        class="bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl px-4 py-3 text-sm flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest ml-1">Email
                        Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 text-sm transition-all
                               focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/50 focus:bg-white/[0.08]"
                        placeholder="admin@example.com">
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-widest ml-1">Security
                        Key</label>
                    <input type="password" name="password" required
                        class="w-full bg-white/5 border border-white/10 text-white rounded-xl px-4 py-3 text-sm transition-all
                               focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500/50 focus:bg-white/[0.08]"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3.5 rounded-xl text-sm font-bold shadow-lg shadow-blue-900/20 transition-all hover:scale-[1.01] active:scale-[0.98]">
                    Authorize & Enter
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-slate-600 mt-8">
            Unauthorized access is logged. <a href="{{ route('login') }}"
                class="text-slate-400 hover:text-blue-400 transition-colors underline decoration-slate-800 underline-offset-4">Return
                to Public Site</a>
        </p>

    </div>
</body>

</html>
