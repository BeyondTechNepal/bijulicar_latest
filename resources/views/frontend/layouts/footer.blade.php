<footer class="w-full bg-[#f1f5f9] pt-10 pb-5 px-6 border-t border-slate-200">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 pb-5 border-b border-slate-300/50">

            <div class="lg:col-span-4 space-y-8">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-slate-900 rounded-xl flex items-center justify-center shadow-lg shadow-slate-300">
                        <svg class="w-6 h-6 text-[#4ade80]" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-black tracking-tighter text-slate-900 uppercase italic">Bijuli<span
                            class="text-[#16a34a]">Car</span></span>
                </div>
                <p class="text-slate-500 text-sm font-medium leading-relaxed max-w-sm">
                    The complete marketplace for all types of vehicles. Comparing, finding, and financing your future
                    drive with precision and ease.
                </p>
                <div class="flex space-x-5">
                    {{-- <!-- Facebook -->
                    <a href="#"
                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#16a34a] hover:border-[#4ade80] transition-all">
                        <i class="fab fa-facebook-f text-xs"></i>
                    </a>
                
                    <!-- Instagram -->
                    <a href="#"
                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#16a34a] hover:border-[#4ade80] transition-all">
                        <i class="fab fa-instagram text-xs"></i>
                    </a>
                
                    <!-- X (Twitter) -->
                    <a href="#"
                        class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#16a34a] hover:border-[#4ade80] transition-all">
                        <i class="fab fa-x-twitter text-xs"></i>
                    </a> --}}
                
                    <div class="flex space-x-5">
                        @foreach($socialLinks as $link)
                            <a href="{{ $link->url }}" target="_blank"
                                class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-[#16a34a] hover:border-[#4ade80] transition-all">

                                <i class="fab {{ $link->icon_class }} text-xs"></i>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Vehicle Types</h4>
                <ul class="space-y-4 text-sm font-bold text-slate-600">
                    <li>
                        <a href="{{ route('marketplace') }}" class="flex items-center gap-2 hover:text-[#16a34a] transition">
                            <span><i class="fa-solid fa-leaf mr-1" style="color: rgb(46, 204, 113);"></i></span>
                            Electric
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('marketplace') }}" class="flex items-center gap-2 hover:text-[#16a34a] transition">
                            <span><i class="fa-solid fa-bolt-lightning mr-1" style="color: rgb(52, 152, 219);"></i></span>
                            Hybrid
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('marketplace') }}" class="flex items-center gap-2 hover:text-[#16a34a] transition">
                            <span><i class="fa-solid fa-gas-pump mr-1" style="color: rgb(231, 76, 60);"></i></span>
                            Traditional
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('marketplace') }}" class="flex items-center gap-2 hover:text-[#16a34a] transition">
                            <span><i class="fa-solid fa-car-side mr-1" style="color: rgb(100, 116, 139);"></i></span>
                            All Inventory
                        </a>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Services</h4>
                <ul class="space-y-4 text-sm font-bold text-slate-600">
                    <li><a href="{{ route('rent') }}" class="hover:text-[#16a34a] transition">Rent a Car</a></li>
                    <li><a href="{{ route('loan_calculator') }}" class="hover:text-[#16a34a] transition">Loan Calculator</a></li>
                    <li><a href="{{ route('compare_cars') }}" class="hover:text-[#16a34a] transition">Compare Models</a></li>
                    <li><a href="{{ route('map_location') }}" class="hover:text-[#16a34a] transition">Map Search</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2"> 
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Support</h4>
                <ul class="space-y-4 text-sm font-bold text-slate-600">
                    <li><a href="{{ route('contact') }}" class="hover:text-[#16a34a] transition">Contact Us</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#16a34a] transition">Help Center</a></li>
                    <li><a href="#" class="hover:text-[#16a34a] transition">Terms of Service</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#16a34a] transition">FAQs</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Newsletter</h4>

                <div class="relative">
                    <form id="newsletter-form" action="{{ route('newsletter.subscribe') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="Your email"
                            class="w-full bg-white border border-slate-200 rounded-xl py-3 px-4 text-xs focus:outline-none focus:border-[#4ade80] transition-all"
                            required>
                        <button type="submit"
                            class="absolute right-1.5 top-1.5 bg-slate-900 text-white p-2 rounded-lg hover:bg-[#16a34a] transition-all">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>

                    <div class="min-h-[20px] mt-3">
                        <p id="newsletter-message" 
                        class="text-[11px] font-medium tracking-wide px-1 hidden items-center gap-2 transition-all duration-300">
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-5 flex flex-col items-center justify-center gap-2 text-center">
        
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                © 2026 Bijulicar.com. All rights reserved.
            </p>
        
            <p class="text-[9px] text-slate-400 flex items-center gap-1">
                Supporting <i class="fa-solid fa-leaf mr-1" style="color: rgb(46, 204, 113);"></i> Complete Mobility
            </p>
        
            <p class="text-[10px] text-slate-500">
                Developed and maintained by
                <a href="https://beyondtechnepal.com" target="_blank" rel="noopener noreferrer"
                class="font-bold text-black hover:underline transition-colors">
                    <span class="text-red-600">Beyond</span> <span class="text-blue-800">Tech Nepal</span>
                </a>
            </p>
        </div>
    </div>
</footer>

{{-- script for ajax email subscription --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('newsletter-form');
        const message = document.getElementById('newsletter-message');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // prevent page reload

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    message.textContent = data.success;
                    message.classList.remove('text-red-600', 'hidden');
                    message.classList.add('text-green-600');
                    form.reset();
                } else if(data.error){
                    message.textContent = data.error;
                    message.classList.remove('text-green-600', 'hidden');
                    message.classList.add('text-red-600');
                }
            })
            .catch(err => {
                message.textContent = 'Something went wrong!';
                message.classList.remove('text-green-600', 'hidden');
                message.classList.add('text-red-600');
                console.error(err);
            });
        });
    });
</script>
