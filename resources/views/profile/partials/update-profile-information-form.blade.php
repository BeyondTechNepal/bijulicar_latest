<section class="max-w-2xl">
    <header class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-8 h-1 bg-[#16a34a] rounded-full"></span>
            <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-[0.3em]">
                {{ __('Account Settings') }}
            </p>
        </div>
        <h2 class="text-3xl font-black text-slate-900 uppercase italic tracking-tighter">
            {{ __('Profile') }} <span class="text-[#16a34a]">{{ __('Information') }}</span>
        </h2>
        <p class="mt-1 text-sm font-medium text-slate-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Profile Photo --}}
        <div class="space-y-3">
            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Profile Photo') }}
            </label>
            <div class="flex items-center gap-5">
                <div class="relative shrink-0">
                    <div id="avatar-wrap" class="w-20 h-20 rounded-2xl overflow-hidden bg-slate-900 flex items-center justify-center shadow-lg">
                        @if($user->profile_photo)
                            <img id="avatar-preview"
                                 src="{{ Storage::url($user->profile_photo) }}"
                                 alt="{{ $user->name }}"
                                 class="w-full h-full object-cover">
                            <span id="avatar-initials" class="text-white text-xl font-black uppercase tracking-wide hidden">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        @else
                            <img id="avatar-preview" src="" alt="" class="w-full h-full object-cover hidden">
                            <span id="avatar-initials" class="text-white text-xl font-black uppercase tracking-wide">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </span>
                        @endif
                    </div>
                    <label for="profile_photo"
                           class="absolute -bottom-1.5 -right-1.5 w-7 h-7 bg-[#16a34a] rounded-xl flex items-center justify-center cursor-pointer shadow-md hover:bg-green-600 transition-colors"
                           title="Upload photo">
                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </label>
                </div>

                <div class="flex-1 space-y-2">
                    <input id="profile_photo" name="profile_photo" type="file"
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden"
                           onchange="previewPhoto(this)" />
                    <p class="text-xs font-bold text-slate-500">Click the camera icon or the button to upload a photo.</p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">JPG, PNG or WEBP &middot; Max 2 MB</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <label for="profile_photo"
                               class="cursor-pointer px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
                            Choose Photo
                        </label>
                        @if($user->profile_photo)
                        <button type="button" onclick="removePhoto()"
                                id="remove-photo-btn"
                                class="px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors">
                            Remove Photo
                        </button>
                        @else
                        <button type="button" onclick="removePhoto()"
                                id="remove-photo-btn"
                                class="hidden px-4 py-2 bg-red-50 border border-red-200 text-red-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-100 transition-colors">
                            Remove Photo
                        </button>
                        @endif
                    </div>
                    <input type="hidden" name="remove_profile_photo" id="remove_profile_photo" value="0">
                    <x-input-error class="mt-1 text-[9px] font-bold uppercase" :messages="$errors->get('profile_photo')" />
                </div>
            </div>
        </div>

        {{-- Full Name --}}
        <div class="space-y-1">
            <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Full Name') }}
            </label>
            <input
                id="name"
                name="name"
                type="text"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('name') border-red-500 @enderror"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2 text-[9px] font-bold uppercase" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div class="space-y-1">
            <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">
                {{ __('Email Address') }}
            </label>
            <input
                id="email"
                name="email"
                type="email"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-[#16a34a] focus:bg-white transition-all font-medium @error('email') border-red-500 @enderror"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2 text-[9px] font-bold uppercase" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="bg-amber-50 border border-amber-100 p-4 rounded-xl mt-4">
                    <p class="text-xs font-bold text-amber-800 uppercase tracking-tight">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification" class="mt-2 text-[10px] font-black text-amber-600 uppercase tracking-widest hover:text-amber-700 underline underline-offset-4">
                        {{ __('Re-send Verification Email') }}
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-black text-[10px] text-green-600 uppercase italic">
                            {{ __('A new link has been dispatched to your inbox.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Submit --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-black uppercase italic tracking-widest text-xs hover:bg-[#16a34a] transition-all shadow-lg flex items-center gap-2 group">
                {{ __('Update Identity') }}
                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest italic"
                >
                    {{ __('✓ Changes Synced') }}
                </p>
            @endif
        </div>
    </form>

    <script>
        function previewPhoto(input) {
            if (!input.files || !input.files[0]) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('avatar-preview');
                const initials = document.getElementById('avatar-initials');
                img.src = e.target.result;
                img.classList.remove('hidden');
                if (initials) initials.classList.add('hidden');
                const removeBtn = document.getElementById('remove-photo-btn');
                if (removeBtn) removeBtn.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
            document.getElementById('remove_profile_photo').value = '0';
        }

        function removePhoto() {
            const img = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            img.src = '';
            img.classList.add('hidden');
            if (initials) initials.classList.remove('hidden');
            document.getElementById('profile_photo').value = '';
            document.getElementById('remove_profile_photo').value = '1';
            const removeBtn = document.getElementById('remove-photo-btn');
            if (removeBtn) removeBtn.classList.add('hidden');
        }
    </script>
</section>