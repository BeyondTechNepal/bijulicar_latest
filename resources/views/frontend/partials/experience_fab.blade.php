{{--
    Car Experience FAB
    ─────────────────
    Floating action button (bottom-right) present on every frontend page.
    Opens a slide-over panel with two tabs:
      • "Experiences" — paginated approved feed, filterable by car / type
      • "Share"       — submit form (auth-gated; guests see a login prompt)

    No Livewire / Alpine component needed — plain Alpine x-data + vanilla JS fetch.
--}}

<div
    x-data="experienceFab()"
    x-init="init()"
    @keydown.escape.window="open && closePanel()"
>

    {{-- ── FAB button ─────────────────────────────────────────────────── --}}
    <button
        @click="togglePanel()"
        title="Car Experiences"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#4ade80] text-black shadow-lg shadow-green-400/40
               flex items-center justify-center
               hover:bg-[#22c55e] hover:scale-110 active:scale-95
               transition-all duration-200"
    >
        {{-- car + sparkle icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M12 3l1.5 4.5H18l-3.75 2.75L15.75 15 12 12.25 8.25 15l1.5-4.75L6 7.5h4.5L12 3z" />
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M5 17h14M7 17l1-3h8l1 3M9 14l1-3h4l1 3" />
        </svg>
    </button>

    {{-- ── Backdrop ────────────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="closePanel()"
        class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm"
        style="display:none"
    ></div>

    {{-- ── Slide-over panel ────────────────────────────────────────────── --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full opacity-0"
        x-transition:enter-end="translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="translate-x-0 opacity-100"
        x-transition:leave-end="translate-x-full opacity-0"
        class="fixed top-0 right-0 z-50 h-full w-full sm:w-[420px] bg-white shadow-2xl flex flex-col"
        style="display:none"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <div>
                <h2 class="text-base font-black text-slate-900 tracking-tight">Car Experiences</h2>
                <p class="text-xs text-slate-400 font-medium mt-0.5">Real stories from real drivers</p>
            </div>
            <button @click="closePanel()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-slate-100 transition-colors">
                <i class="fa-solid fa-xmark text-slate-500 text-sm"></i>
            </button>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-slate-100 px-5">
            <button
                @click="tab = 'feed'"
                :class="tab === 'feed'
                    ? 'border-b-2 border-[#4ade80] text-slate-900 font-black'
                    : 'text-slate-400 font-semibold hover:text-slate-600'"
                class="py-3 mr-6 text-sm transition-colors"
            >
                <i class="fa-solid fa-newspaper mr-1.5"></i> Experiences
            </button>
            <button
                @click="tab = 'share'"
                :class="tab === 'share'
                    ? 'border-b-2 border-[#4ade80] text-slate-900 font-black'
                    : 'text-slate-400 font-semibold hover:text-slate-600'"
                class="py-3 text-sm transition-colors"
            >
                <i class="fa-solid fa-pen-to-square mr-1.5"></i> Share
            </button>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             TAB: FEED
        ══════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'feed'" class="flex flex-col flex-1 overflow-hidden">

            {{-- Filters --}}
            <div class="px-5 py-3 border-b border-slate-50 space-y-2">
                {{-- Car search --}}
                <div class="relative">
                    <input
                        x-model="feedFilter.search"
                        @input.debounce.400ms="feedPage = 1; loadFeed()"
                        type="text"
                        placeholder="Filter by car name…"
                        class="w-full pl-9 pr-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                    />
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
                {{-- Type filter --}}
                <div class="flex gap-2">
                    <template x-for="t in [{val:'',label:'All'},{val:'rental',label:'Rental'},{val:'purchase',label:'Purchase'},{val:'general',label:'General'}]" :key="t.val">
                        <button
                            @click="feedFilter.type = t.val; feedPage = 1; loadFeed()"
                            :class="feedFilter.type === t.val
                                ? 'bg-[#4ade80] text-black font-black'
                                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                            class="px-3 py-1 rounded-full text-xs font-semibold transition-colors"
                            x-text="t.label"
                        ></button>
                    </template>
                </div>
            </div>

            {{-- Feed list --}}
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">

                {{-- Loading --}}
                <div x-show="feedLoading" class="flex justify-center py-10">
                    <div class="w-6 h-6 border-2 border-[#4ade80] border-t-transparent rounded-full animate-spin"></div>
                </div>

                {{-- Empty --}}
                <div x-show="!feedLoading && experiences.length === 0" class="text-center py-10">
                    <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                        <i class="fa-solid fa-road text-slate-400 text-xl"></i>
                    </div>
                    <p class="text-slate-500 text-sm font-semibold">No experiences yet</p>
                    <p class="text-slate-400 text-xs mt-1">Be the first to share your story!</p>
                </div>

                {{-- Cards --}}
                <template x-for="exp in experiences" :key="exp.id">
                    <div class="border border-slate-100 rounded-2xl p-4 hover:border-slate-200 hover:shadow-sm transition-all">

                        {{-- Top row: author + badges --}}
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-full bg-slate-900 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[10px] font-black text-white" x-text="(exp.user?.name ?? 'U').substring(0,2).toUpperCase()"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate" x-text="exp.user?.name ?? 'User'"></p>
                                    <p class="text-[10px] text-slate-400" x-text="formatDate(exp.approved_at ?? exp.created_at)"></p>
                                </div>
                            </div>
                            <span
                                class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider flex-shrink-0"
                                :class="typeBadge(exp.experience_type)"
                                x-text="exp.experience_type"
                            ></span>
                        </div>

                        {{-- Car name --}}
                        <p class="text-[11px] text-[#16a34a] font-bold mb-1 flex items-center gap-1">
                            <i class="fa-solid fa-car text-[10px]"></i>
                            <span x-text="exp.car ? (exp.car.year + ' ' + exp.car.brand + ' ' + exp.car.model + (exp.car.variant ? ' ' + exp.car.variant : '')) : exp.external_car_name"></span>
                        </p>

                        {{-- Title --}}
                        <h3 class="text-sm font-black text-slate-900 leading-snug mb-1" x-text="exp.title"></h3>

                        {{-- Trip context --}}
                        <p x-show="exp.trip_context" class="text-[11px] text-slate-400 italic mb-2" x-text="'📍 ' + exp.trip_context"></p>

                        {{-- Body — collapsed/expanded --}}
                        <div x-data="{ expanded: false }">
                            <p
                                class="text-xs text-slate-600 leading-relaxed"
                                :class="expanded ? '' : 'line-clamp-3'"
                                x-text="exp.body"
                            ></p>
                            <button
                                x-show="exp.body && exp.body.length > 180"
                                @click="expanded = !expanded"
                                class="text-[11px] text-[#16a34a] font-bold mt-1 hover:underline"
                                x-text="expanded ? 'Show less ↑' : 'Read more ↓'"
                            ></button>
                        </div>
                    </div>
                </template>

                {{-- Pagination --}}
                <div x-show="!feedLoading && (feedPage > 1 || hasMore)" class="flex items-center justify-between pt-2">
                    <button
                        @click="feedPage--; loadFeed()"
                        x-show="feedPage > 1"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors"
                    >
                        ← Prev
                    </button>
                    <span class="text-[11px] text-slate-400 font-medium" x-text="'Page ' + feedPage"></span>
                    <button
                        @click="feedPage++; loadFeed()"
                        x-show="hasMore"
                        class="text-xs font-bold text-[#16a34a] hover:text-[#15803d] transition-colors"
                    >
                        Next →
                    </button>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             TAB: SHARE
        ══════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'share'" class="flex-1 overflow-y-auto">

            @guest
            {{-- Guest prompt --}}
            <div class="flex flex-col items-center justify-center h-full px-8 py-16 text-center">
                <div class="w-16 h-16 rounded-full bg-[#4ade80]/10 flex items-center justify-center mb-4">
                    <i class="fa-solid fa-lock text-[#16a34a] text-2xl"></i>
                </div>
                <h3 class="text-base font-black text-slate-900 mb-2">Sign in to share</h3>
                <p class="text-sm text-slate-500 leading-relaxed mb-6">
                    Share your real car experience with the BijuliCar community. Login required.
                </p>
                <a href="{{ route('login') }}"
                   class="bg-[#4ade80] text-black font-black px-6 py-2.5 rounded-xl text-sm hover:bg-[#22c55e] transition-colors">
                    Login to Share
                </a>
            </div>
            @endguest

            @auth
            {{-- Share form --}}
            <form
                @submit.prevent="submitExperience()"
                class="px-5 py-5 space-y-4"
                novalidate
            >

                {{-- Success message --}}
                <div
                    x-show="formSuccess"
                    x-transition
                    class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700 font-semibold"
                    style="display:none"
                >
                    <i class="fa-solid fa-circle-check mr-1.5"></i>
                    <span x-text="formSuccess"></span>
                </div>

                {{-- Error message --}}
                <div
                    x-show="formError"
                    x-transition
                    class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600 font-semibold"
                    style="display:none"
                    x-text="formError"
                ></div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Title <span class="text-red-500">*</span></label>
                    <input
                        x-model="form.title"
                        type="text"
                        maxlength="150"
                        placeholder="e.g. Best highway drive of my life"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                    />
                    <p x-show="fieldErrors.title" class="text-red-500 text-xs mt-1" x-text="fieldErrors.title"></p>
                </div>

                {{-- Experience type --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Type <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <template x-for="t in [{val:'rental',label:'Rental'},{val:'purchase',label:'Purchase'},{val:'general',label:'General'}]" :key="t.val">
                            <button
                                type="button"
                                @click="form.experience_type = t.val"
                                :class="form.experience_type === t.val
                                    ? 'bg-[#4ade80] text-black font-black ring-2 ring-[#4ade80]/40'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="flex-1 py-2 rounded-xl text-xs font-semibold transition-all"
                                x-text="t.label"
                            ></button>
                        </template>
                    </div>
                </div>

                {{-- Trip context (optional) --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">
                        Trip / Context <span class="text-slate-400 font-medium normal-case">(optional)</span>
                    </label>
                    <input
                        x-model="form.trip_context"
                        type="text"
                        maxlength="150"
                        placeholder="e.g. Kathmandu to Pokhara road trip"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                    />
                </div>

                {{-- Car link toggle --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Car <span class="text-red-500">*</span></label>
                    <div class="flex rounded-xl overflow-hidden border border-slate-200">
                        <button
                            type="button"
                            @click="form.linked_to_bijulicar = true; form.external_car_name = ''"
                            :class="form.linked_to_bijulicar ? 'bg-slate-900 text-white font-black' : 'bg-white text-slate-500 hover:bg-slate-50'"
                            class="flex-1 py-2 text-xs font-semibold transition-all"
                        >
                            <i class="fa-solid fa-link mr-1"></i> BijuliCar Listing
                        </button>
                        <button
                            type="button"
                            @click="form.linked_to_bijulicar = false; form.car_id = null; carSearchQuery = ''; carSearchResults = []"
                            :class="!form.linked_to_bijulicar ? 'bg-slate-900 text-white font-black' : 'bg-white text-slate-500 hover:bg-slate-50'"
                            class="flex-1 py-2 text-xs font-semibold transition-all"
                        >
                            <i class="fa-solid fa-pen mr-1"></i> Other Car
                        </button>
                    </div>

                    {{-- BijuliCar car search --}}
                    <div x-show="form.linked_to_bijulicar" class="mt-2 relative">
                        <input
                            x-model="carSearchQuery"
                            @input.debounce.350ms="searchCars()"
                            @focus="carSearchQuery.length > 0 && searchCars()"
                            type="text"
                            placeholder="Search cars on BijuliCar…"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                        />
                        {{-- Dropdown results --}}
                        <div
                            x-show="carSearchResults.length > 0"
                            class="absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-10 max-h-48 overflow-y-auto"
                            style="display:none"
                        >
                            <template x-for="car in carSearchResults" :key="car.id">
                                <button
                                    type="button"
                                    @click="selectCar(car)"
                                    class="w-full text-left px-3 py-2.5 text-xs hover:bg-slate-50 transition-colors border-b border-slate-50 last:border-0"
                                    x-text="car.name"
                                ></button>
                            </template>
                        </div>
                        {{-- Selected car chip --}}
                        <div x-show="form.car_id" class="mt-2 flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                            <i class="fa-solid fa-circle-check text-green-600 text-xs"></i>
                            <span class="text-xs text-green-700 font-bold flex-1" x-text="selectedCarName"></span>
                            <button type="button" @click="form.car_id = null; selectedCarName = ''; carSearchQuery = ''" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <p x-show="fieldErrors.car_id" class="text-red-500 text-xs mt-1" x-text="fieldErrors.car_id"></p>
                    </div>

                    {{-- External car name --}}
                    <div x-show="!form.linked_to_bijulicar" class="mt-2">
                        <input
                            x-model="form.external_car_name"
                            type="text"
                            maxlength="100"
                            placeholder="e.g. Tata Nexon EV, Honda City 2023…"
                            class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                        />
                        <p x-show="fieldErrors.external_car_name" class="text-red-500 text-xs mt-1" x-text="fieldErrors.external_car_name"></p>
                    </div>
                </div>

                {{-- Body --}}
                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Your Experience <span class="text-red-500">*</span></label>
                    <textarea
                        x-model="form.body"
                        rows="6"
                        maxlength="3000"
                        placeholder="Tell us about your experience with this car — performance, comfort, issues, highlights…"
                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"
                    ></textarea>
                    <div class="flex justify-between mt-1">
                        <p x-show="fieldErrors.body" class="text-red-500 text-xs" x-text="fieldErrors.body"></p>
                        <p class="text-xs text-slate-400 ml-auto" x-text="form.body.length + ' / 3000'"></p>
                    </div>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    :disabled="formSubmitting"
                    class="w-full bg-[#4ade80] text-black font-black py-3 rounded-xl text-sm
                           hover:bg-[#22c55e] active:scale-95 transition-all
                           disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span x-show="!formSubmitting"><i class="fa-solid fa-paper-plane mr-2"></i>Submit Experience</span>
                    <span x-show="formSubmitting"><i class="fa-solid fa-spinner fa-spin mr-2"></i>Submitting…</span>
                </button>

                <p class="text-[11px] text-slate-400 text-center leading-relaxed">
                    Experiences are reviewed by our team before going live.
                </p>

            </form>
            @endauth
        </div>

    </div>{{-- end slide-over --}}

</div>{{-- end x-data --}}


<script>
function experienceFab() {
    return {
        // ── Panel state ────────────────────────────────────────────────
        open: false,
        tab:  'feed',

        // ── Feed state ─────────────────────────────────────────────────
        experiences:  [],
        feedLoading:  false,
        feedPage:     1,
        hasMore:      false,
        feedFilter:   { search: '', type: '' },

        // ── Car search state ───────────────────────────────────────────
        carSearchQuery:   '',
        carSearchResults: [],
        selectedCarName:  '',

        // ── Form state ─────────────────────────────────────────────────
        form: {
            title:               '',
            trip_context:        '',
            body:                '',
            experience_type:     'general',
            linked_to_bijulicar: false,
            car_id:              null,
            external_car_name:   '',
        },
        fieldErrors:     {},
        formError:       '',
        formSuccess:     '',
        formSubmitting:  false,

        // ── Init ───────────────────────────────────────────────────────
        init() {
            // Pre-load feed when component mounts so it's instant on first open
            this.loadFeed();
        },

        // ── Panel controls ─────────────────────────────────────────────
        togglePanel() {
            this.open = !this.open;
            if (this.open) document.body.style.overflow = 'hidden';
            else           this.restoreScroll();
        },
        closePanel() {
            this.open = false;
            this.restoreScroll();
        },
        restoreScroll() {
            document.body.style.overflow = '';
        },

        // ── Feed ───────────────────────────────────────────────────────
        async loadFeed() {
            this.feedLoading = true;
            this.experiences = [];

            const params = new URLSearchParams({
                page: this.feedPage,
            });
            if (this.feedFilter.search) params.set('search', this.feedFilter.search);
            if (this.feedFilter.type)   params.set('type',   this.feedFilter.type);

            try {
                const res  = await fetch(`/experiences?${params}`);
                const data = await res.json();
                this.experiences = data.data ?? [];
                this.hasMore     = !!data.next_page_url;
            } catch (e) {
                console.error('Experience feed error:', e);
            } finally {
                this.feedLoading = false;
            }
        },

        // ── Car search ─────────────────────────────────────────────────
        async searchCars() {
            if (this.carSearchQuery.length < 1) {
                this.carSearchResults = [];
                return;
            }
            try {
                const res  = await fetch(`/experiences/cars?q=${encodeURIComponent(this.carSearchQuery)}`);
                this.carSearchResults = await res.json();
            } catch (e) {
                this.carSearchResults = [];
            }
        },
        selectCar(car) {
            this.form.car_id    = car.id;
            this.selectedCarName = car.name;
            this.carSearchResults = [];
            this.carSearchQuery   = '';
        },

        // ── Submit ─────────────────────────────────────────────────────
        async submitExperience() {
            this.fieldErrors    = {};
            this.formError      = '';
            this.formSuccess    = '';
            this.formSubmitting = true;

            const payload = {
                title:               this.form.title,
                trip_context:        this.form.trip_context,
                body:                this.form.body,
                experience_type:     this.form.experience_type,
                linked_to_bijulicar: this.form.linked_to_bijulicar ? 1 : 0,
                car_id:              this.form.linked_to_bijulicar ? this.form.car_id : null,
                external_car_name:   !this.form.linked_to_bijulicar ? this.form.external_car_name : null,
            };

            try {
                const res  = await fetch('/experiences', {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept':       'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();

                if (res.ok) {
                    // Success — reset form, show message
                    this.formSuccess = data.message;
                    this.resetForm();
                } else if (res.status === 422) {
                    // Validation errors
                    const errors = data.errors ?? {};
                    this.fieldErrors = Object.fromEntries(
                        Object.entries(errors).map(([k, v]) => [k, Array.isArray(v) ? v[0] : v])
                    );
                    this.formError = data.message ?? 'Please fix the errors below.';
                } else {
                    this.formError = data.message ?? 'Something went wrong. Please try again.';
                }
            } catch (e) {
                this.formError = 'Network error. Please check your connection.';
            } finally {
                this.formSubmitting = false;
            }
        },

        resetForm() {
            this.form = {
                title:               '',
                trip_context:        '',
                body:                '',
                experience_type:     'general',
                linked_to_bijulicar: false,
                car_id:              null,
                external_car_name:   '',
            };
            this.carSearchQuery   = '';
            this.carSearchResults = [];
            this.selectedCarName  = '';
            this.fieldErrors      = {};
            this.formError        = '';
        },

        // ── Helpers ────────────────────────────────────────────────────
        formatDate(dateStr) {
            if (!dateStr) return '';
            const d = new Date(dateStr);
            return d.toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
        },
        typeBadge(type) {
            const map = {
                rental:   'bg-blue-100 text-blue-700',
                purchase: 'bg-green-100 text-green-700',
                general:  'bg-slate-100 text-slate-600',
            };
            return map[type] ?? 'bg-slate-100 text-slate-600';
        },
    };
}
</script>