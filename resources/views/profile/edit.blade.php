<x-app-layout>
    <x-slot name="header">
    <div class="flex items-center justify-between">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="w-10 h-1 bg-[#16a34a] rounded-full"></span>
                <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-[0.4em]">
                    {{ __('System Control') }}
                </p>
            </div>
            <h2 class="text-4xl font-black text-slate-900 uppercase italic tracking-tighter leading-none">
                User <span class="text-[#16a34a]">{{ __('Profile') }}</span>
            </h2>
        </div>
        
        <div class="hidden md:block text-right">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1 italic">Access Level: Authorized</span>
            <div class="w-32 h-1 bg-slate-100 rounded-full overflow-hidden">
                <div class="w-full h-full bg-[#16a34a] shadow-[0_0_8px_rgba(22,163,74,0.4)]"></div>
            </div>
        </div>
    </div>
</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
