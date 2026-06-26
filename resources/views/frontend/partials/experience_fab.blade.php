{{--
    Car Experience FAB
    ─────────────────
    Floating action button (bottom-right) present on every frontend page.
    Opens a slide-over panel with two tabs:
      • "Experiences" — paginated approved feed, filterable by car / type
                        each card has a Facebook-style comment section
      • "Share"       — submit form (auth-gated; guests see a login prompt)

    No Livewire / Alpine component needed — plain Alpine x-data + vanilla JS fetch.
--}}

<div
    x-data="experienceFab"
    x-init="init()"
    @keydown.escape.window="open && closePanel()"
>

    {{-- ── FAB button ─────────────────────────────────────────────────── --}}

    {{-- Cycling label above the FAB --}}
    <div
        id="fab-label"
        class="fixed bottom-24 right-4 z-50 pointer-events-none"
        style="opacity:0; transform: translateY(6px); transition: opacity 0.5s ease, transform 0.5s ease;"
    >
        <div class="bg-slate-900 text-white text-xs font-black px-3 py-1.5 rounded-full shadow-lg whitespace-nowrap flex items-center gap-1.5">
            <i class="fa-solid fa-star text-[#4ade80] text-[10px]"></i>
            Share your experience
        </div>
        {{-- small arrow pointing down to the FAB --}}
        <div class="flex justify-end pr-4">
            <div class="w-0 h-0 border-l-4 border-r-4 border-t-4 border-l-transparent border-r-transparent border-t-slate-900"></div>
        </div>
    </div>

    <button
        @click="togglePanel()"
        title="Car Experiences"
        class="fixed bottom-6 right-6 z-50 w-14 h-14 rounded-full bg-[#4ade80] text-black shadow-lg shadow-green-400/40
               flex items-center justify-center
               hover:bg-[#22c55e] hover:scale-110 active:scale-95
               transition-all duration-200"
    >
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
                <div class="flex gap-2">
                    <template x-for="t in [{val:'',label:'All'},{val:'rental',label:'Rental'},{val:'purchase',label:'Ride'},{val:'general',label:'General'}]" :key="t.val">
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

                {{-- ── Experience cards ──────────────────────────────── --}}
                <template x-for="exp in experiences" :key="exp.id">
                    <div
                        x-data="{
                            commentsOpen: false,
                            commentsLoading: false,
                            comments: [],
                            commentCount: exp.comments_count ?? 0,
                            newCommentBody: '',
                            replyingToId: null,
                            replyBody: '',
                            editingId: null,
                            editBody: '',
                            commentSubmitting: false,

                            async toggleComments() {
                                this.commentsOpen = !this.commentsOpen;
                                if (this.commentsOpen && this.comments.length === 0) {
                                    await this.loadComments();
                                }
                            },
                            async loadComments() {
                                this.commentsLoading = true;
                                try {
                                    const res = await fetch('/experiences/' + exp.id + '/comments');
                                    this.comments = await res.json();
                                    this.commentCount = this.comments.reduce((sum, c) => sum + 1 + (c.replies?.length ?? 0), 0);
                                } catch(e) { console.error(e); }
                                finally { this.commentsLoading = false; }
                            },
                            startReply(comment) { this.replyingToId = comment.id; this.replyBody = ''; this.editingId = null; },
                            startEdit(comment) { this.editingId = comment.id; this.editBody = comment.body; this.replyingToId = null; },
                            async postComment(parentId) {
                                const body = parentId ? this.replyBody : this.newCommentBody;
                                if (!body.trim()) return;
                                this.commentSubmitting = true;
                                try {
                                    const res = await fetch('/experiences/' + exp.id + '/comments', {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                        body: JSON.stringify({ body, parent_id: parentId })
                                    });
                                    if (res.ok) {
                                        const c = await res.json();
                                        if (parentId) {
                                            const parent = this.comments.find(c => c.id === parentId);
                                            if (parent) { if (!parent.replies) parent.replies = []; parent.replies.push(c); }
                                            this.replyingToId = null; this.replyBody = '';
                                        } else {
                                            c.replies = []; this.comments.push(c); this.newCommentBody = '';
                                        }
                                        this.commentCount++;
                                    } else if (res.status === 401) { window.location.href = '/login'; }
                                } catch(e) { console.error(e); }
                                finally { this.commentSubmitting = false; }
                            },
                            async saveEdit(comment) {
                                if (!this.editBody.trim()) return;
                                try {
                                    const res = await fetch('/experience-comments/' + comment.id, {
                                        method: 'PATCH',
                                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                                        body: JSON.stringify({ body: this.editBody })
                                    });
                                    if (res.ok) { const d = await res.json(); comment.body = d.body; comment.is_edited = d.is_edited; this.editingId = null; }
                                } catch(e) { console.error(e); }
                            },
                            async deleteComment(comment) {
                                if (!confirm('Delete this comment?')) return;
                                try {
                                    const res = await fetch('/experience-comments/' + comment.id, {
                                        method: 'DELETE',
                                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                    });
                                    if (res.ok) {
                                        if (comment.parent_id) {
                                            const parent = this.comments.find(c => c.id === comment.parent_id);
                                            if (parent) parent.replies = parent.replies.filter(r => r.id !== comment.id);
                                        } else {
                                            const c = this.comments.find(c => c.id === comment.id);
                                            if (c) this.commentCount -= 1 + (c.replies?.length ?? 0);
                                            this.comments = this.comments.filter(c => c.id !== comment.id);
                                            return;
                                        }
                                        this.commentCount--;
                                    }
                                } catch(e) { console.error(e); }
                            },
                            formatDate(dateStr) {
                                if (!dateStr) return '';
                                return new Date(dateStr).toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' });
                            },
                            typeBadge(type) {
                                const map = { rental:'bg-blue-100 text-blue-700', purchase:'bg-green-100 text-green-700', general:'bg-slate-100 text-slate-600' };
                                return map[type] ?? 'bg-slate-100 text-slate-600';
                            },
                            typeLabel(type) {
                                const map = { rental:'Rental', purchase:'Ride', general:'General' };
                                return map[type] ?? type;
                            },
                            async deleteExperience(exp) {
                                if (!confirm('Delete this experience? This cannot be undone.')) return;
                                try {
                                    const res = await fetch('/experiences/' + exp.id, {
                                        method: 'DELETE',
                                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
                                    });
                                    if (res.ok) {
                                        this.$parent.experiences = this.$parent.experiences.filter(e => e.id !== exp.id);
                                    } else {
                                        alert('Could not delete this experience. Please try again.');
                                    }
                                } catch(e) { console.error(e); }
                            }
                        }"
                        class="border border-slate-100 rounded-2xl overflow-hidden hover:border-slate-200 hover:shadow-sm transition-all"
                    >
                        {{-- Card body --}}
                        <div class="p-4">
                            {{-- Top row: author + badge --}}
                            <div class="flex items-start justify-between gap-2 mb-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-7 h-7 rounded-full bg-slate-900 flex items-center justify-center flex-shrink-0">
                                        <span class="text-[10px] font-black text-white"
                                              x-text="(exp.author_name ?? exp.user?.name ?? 'U').substring(0,2).toUpperCase()"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-slate-800 truncate"
                                           x-text="exp.author_name ?? exp.user?.name ?? 'User'"></p>
                                        <p class="text-[10px] text-slate-400"
                                           x-text="formatDate(exp.approved_at ?? exp.created_at)"></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1.5 flex-shrink-0">
                                    <span
                                        class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                                        :class="typeBadge(exp.experience_type)"
                                        x-text="typeLabel(exp.experience_type)"
                                    ></span>
                                    <button
                                        x-show="exp.is_mine"
                                        @click="deleteExperience(exp)"
                                        title="Delete your experience"
                                        class="w-6 h-6 flex items-center justify-center rounded-full text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors"
                                    >
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Car name --}}
                            <p class="text-[11px] text-[#16a34a] font-bold mb-1 flex items-center gap-1">
                                <i class="fa-solid fa-car text-[10px]"></i>
                                <span x-text="exp.car ? (exp.car.year + ' ' + exp.car.brand + ' ' + exp.car.model + (exp.car.variant ? ' ' + exp.car.variant : '')) : exp.external_car_name"></span>
                            </p>

                            {{-- Title --}}
                            <h3 class="text-sm font-black text-slate-900 leading-snug mb-1" x-text="exp.title"></h3>

                            {{-- Trip context --}}
                            <p x-show="exp.trip_context" class="text-[11px] text-slate-400 italic mb-2"
                               x-text="'📍 ' + exp.trip_context"></p>

                            {{-- Body collapsed/expanded --}}
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

                            {{-- Comment toggle button --}}
                            <button
                                @click="toggleComments()"
                                class="mt-3 flex items-center gap-1.5 text-[11px] font-bold text-slate-400 hover:text-slate-600 transition-colors"
                            >
                                <i class="fa-regular fa-comment text-[11px]"></i>
                                <span x-text="commentCount > 0 ? commentCount + ' comment' + (commentCount !== 1 ? 's' : '') : 'Comment'"></span>
                                <i :class="commentsOpen ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-[9px] ml-0.5"></i>
                            </button>
                        </div>

                        {{-- ── Comment section ──────────────────────── --}}
                        <div x-show="commentsOpen" x-transition class="border-t border-slate-100 bg-slate-50/50">

                            {{-- Comment loading --}}
                            <div x-show="commentsLoading" class="flex justify-center py-4">
                                <div class="w-4 h-4 border-2 border-[#4ade80] border-t-transparent rounded-full animate-spin"></div>
                            </div>

                            {{-- Comments list --}}
                            <div x-show="!commentsLoading" class="px-4 pt-3 pb-2 space-y-3 max-h-64 overflow-y-auto">

                                {{-- Empty comments --}}
                                <p x-show="comments.length === 0"
                                   class="text-[11px] text-slate-400 text-center py-2">
                                    No comments yet. Be the first!
                                </p>

                                {{-- Top-level comments --}}
                                <template x-for="c in comments" :key="c.id">
                                    <div>
                                        {{-- Comment --}}
                                        <div class="flex gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span class="text-[8px] font-black text-white"
                                                      x-text="(c.user?.name ?? 'U').substring(0,2).toUpperCase()"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="bg-white rounded-xl px-3 py-2 border border-slate-100">
                                                    <p class="text-[11px] font-black text-slate-800" x-text="c.user?.name ?? 'User'"></p>
                                                    {{-- View mode --}}
                                                    <p x-show="editingId !== c.id"
                                                       class="text-xs text-slate-600 leading-relaxed mt-0.5"
                                                       x-text="c.body"></p>
                                                    {{-- Edit mode --}}
                                                    <div x-show="editingId === c.id" class="mt-1">
                                                        <textarea
                                                            x-model="editBody"
                                                            rows="2"
                                                            maxlength="1000"
                                                            class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg resize-none focus:outline-none focus:ring-1 focus:ring-[#4ade80] focus:border-[#4ade80]"
                                                        ></textarea>
                                                        <div class="flex gap-2 mt-1">
                                                            <button @click="saveEdit(c)"
                                                                class="text-[10px] font-black text-white bg-[#4ade80] px-2 py-1 rounded-lg hover:bg-[#22c55e] transition-colors">
                                                                Save
                                                            </button>
                                                            <button @click="editingId = null"
                                                                class="text-[10px] font-bold text-slate-400 hover:text-slate-600">
                                                                Cancel
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- Meta row --}}
                                                <div class="flex items-center gap-3 mt-1 px-1">
                                                    <span class="text-[10px] text-slate-400" x-text="formatDate(c.created_at)"></span>
                                                    <span x-show="c.is_edited" class="text-[10px] text-slate-400 italic">edited</span>
                                                    @auth
                                                    <button @click="startReply(c)"
                                                        class="text-[10px] font-black text-[#16a34a] hover:underline">
                                                        Reply
                                                    </button>
                                                    <button x-show="c.is_mine" @click="startEdit(c)"
                                                        class="text-[10px] font-bold text-slate-400 hover:text-slate-600">
                                                        Edit
                                                    </button>
                                                    <button x-show="c.is_mine" @click="deleteComment(c)"
                                                        class="text-[10px] font-bold text-red-400 hover:text-red-600">
                                                        Delete
                                                    </button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Replies --}}
                                        <template x-for="r in c.replies" :key="r.id">
                                            <div class="flex gap-2 mt-2 ml-8">
                                                <div class="w-5 h-5 rounded-full bg-slate-600 flex items-center justify-center flex-shrink-0 mt-0.5">
                                                    <span class="text-[7px] font-black text-white"
                                                          x-text="(r.user?.name ?? 'U').substring(0,2).toUpperCase()"></span>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="bg-white rounded-xl px-3 py-2 border border-slate-100">
                                                        <p class="text-[11px] font-black text-slate-800" x-text="r.user?.name ?? 'User'"></p>
                                                        {{-- View mode --}}
                                                        <p x-show="editingId !== r.id"
                                                           class="text-xs text-slate-600 leading-relaxed mt-0.5"
                                                           x-text="r.body"></p>
                                                        {{-- Edit mode --}}
                                                        <div x-show="editingId === r.id" class="mt-1">
                                                            <textarea
                                                                x-model="editBody"
                                                                rows="2"
                                                                maxlength="1000"
                                                                class="w-full px-2 py-1.5 text-xs border border-slate-200 rounded-lg resize-none focus:outline-none focus:ring-1 focus:ring-[#4ade80] focus:border-[#4ade80]"
                                                            ></textarea>
                                                            <div class="flex gap-2 mt-1">
                                                                <button @click="saveEdit(r)"
                                                                    class="text-[10px] font-black text-white bg-[#4ade80] px-2 py-1 rounded-lg hover:bg-[#22c55e] transition-colors">
                                                                    Save
                                                                </button>
                                                                <button @click="editingId = null"
                                                                    class="text-[10px] font-bold text-slate-400 hover:text-slate-600">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-3 mt-1 px-1">
                                                        <span class="text-[10px] text-slate-400" x-text="formatDate(r.created_at)"></span>
                                                        <span x-show="r.is_edited" class="text-[10px] text-slate-400 italic">edited</span>
                                                        @auth
                                                        <button x-show="r.is_mine" @click="startEdit(r)"
                                                            class="text-[10px] font-bold text-slate-400 hover:text-slate-600">
                                                            Edit
                                                        </button>
                                                        <button x-show="r.is_mine" @click="deleteComment(r)"
                                                            class="text-[10px] font-bold text-red-400 hover:text-red-600">
                                                            Delete
                                                        </button>
                                                        @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Inline reply box (shown under the parent being replied to) --}}
                                        @auth
                                        <div x-show="replyingToId === c.id" class="mt-2 ml-8 flex gap-2" x-transition>
                                            <div class="w-5 h-5 rounded-full bg-[#4ade80] flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <span class="text-[7px] font-black text-black">
                                                    {{ substr(auth()->user()->name, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <textarea
                                                    x-model="replyBody"
                                                    @keydown.enter.prevent="if($event.ctrlKey || $event.metaKey) postComment(c.id)"
                                                    rows="2"
                                                    maxlength="1000"
                                                    placeholder="Write a reply… (Ctrl+Enter to send)"
                                                    class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl resize-none focus:outline-none focus:ring-1 focus:ring-[#4ade80] focus:border-[#4ade80]"
                                                ></textarea>
                                                <div class="flex gap-2 mt-1">
                                                    <button
                                                        @click="postComment(c.id)"
                                                        :disabled="commentSubmitting"
                                                        class="text-[10px] font-black text-black bg-[#4ade80] px-3 py-1.5 rounded-lg hover:bg-[#22c55e] transition-colors disabled:opacity-60"
                                                    >
                                                        <span x-show="!commentSubmitting">Reply</span>
                                                        <span x-show="commentSubmitting"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                                    </button>
                                                    <button @click="replyingToId = null; replyBody = ''"
                                                        class="text-[10px] font-bold text-slate-400 hover:text-slate-600">
                                                        Cancel
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        @endauth
                                    </div>
                                </template>
                            </div>

                            {{-- New top-level comment box --}}
                            @auth
                            <div class="px-4 pb-3 pt-2 border-t border-slate-100">
                                <div class="flex gap-2">
                                    <div class="w-6 h-6 rounded-full bg-[#4ade80] flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <span class="text-[8px] font-black text-black">
                                            {{ substr(auth()->user()->name, 0, 2) }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <textarea
                                            x-model="newCommentBody"
                                            @keydown.enter.prevent="if($event.ctrlKey || $event.metaKey) postComment(null)"
                                            rows="2"
                                            maxlength="1000"
                                            placeholder="Write a comment… (Ctrl+Enter to send)"
                                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl resize-none focus:outline-none focus:ring-1 focus:ring-[#4ade80] focus:border-[#4ade80]"
                                        ></textarea>
                                        <div class="flex justify-end mt-1">
                                            <button
                                                @click="postComment(null)"
                                                :disabled="commentSubmitting || !newCommentBody.trim()"
                                                class="text-[10px] font-black text-black bg-[#4ade80] px-3 py-1.5 rounded-lg hover:bg-[#22c55e] transition-colors disabled:opacity-50"
                                            >
                                                <span x-show="!commentSubmitting"><i class="fa-solid fa-paper-plane mr-1"></i>Post</span>
                                                <span x-show="commentSubmitting"><i class="fa-solid fa-spinner fa-spin"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endauth

                            @guest
                            <div class="px-4 py-3 border-t border-slate-100 text-center">
                                <p class="text-[11px] text-slate-400">
                                    <a href="{{ route('login') }}" class="text-[#16a34a] font-bold hover:underline">Login</a>
                                    to leave a comment
                                </p>
                            </div>
                            @endguest
                        </div>
                        {{-- end comment section --}}

                    </div>
                </template>

                {{-- Pagination --}}
                <div x-show="!feedLoading && (feedPage > 1 || hasMore)" class="flex items-center justify-between pt-2">
                    <button
                        @click="feedPage--; loadFeed()"
                        x-show="feedPage > 1"
                        class="text-xs font-bold text-slate-500 hover:text-slate-800 transition-colors"
                    >← Prev</button>
                    <span class="text-[11px] text-slate-400 font-medium" x-text="'Page ' + feedPage"></span>
                    <button
                        @click="feedPage++; loadFeed()"
                        x-show="hasMore"
                        class="text-xs font-bold text-[#16a34a] hover:text-[#15803d] transition-colors"
                    >Next →</button>
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════════════════════════════
             TAB: SHARE
        ══════════════════════════════════════════════════════════════════ --}}
        <div x-show="tab === 'share'" class="flex-1 overflow-y-auto">

            @guest
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
            <form @submit.prevent="submitExperience()" class="px-5 py-5 space-y-4" novalidate>

                <div x-show="formSuccess" x-transition
                     class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700 font-semibold"
                     style="display:none">
                    <i class="fa-solid fa-circle-check mr-1.5"></i>
                    <span x-text="formSuccess"></span>
                </div>

                <div x-show="formError" x-transition
                     class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-600 font-semibold"
                     style="display:none" x-text="formError"></div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Title <span class="text-red-500">*</span></label>
                    <input x-model="form.title" type="text" maxlength="150"
                           placeholder="e.g. Best highway drive of my life"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]" />
                    <p x-show="fieldErrors.title" class="text-red-500 text-xs mt-1" x-text="fieldErrors.title"></p>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Type <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <template x-for="t in [{val:'rental',label:'Rental'},{val:'purchase',label:'Ride'},{val:'general',label:'General'}]" :key="t.val">
                            <button type="button" @click="form.experience_type = t.val"
                                :class="form.experience_type === t.val
                                    ? 'bg-[#4ade80] text-black font-black ring-2 ring-[#4ade80]/40'
                                    : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                class="flex-1 py-2 rounded-xl text-xs font-semibold transition-all"
                                x-text="t.label"></button>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">
                        Trip / Context <span class="text-slate-400 font-medium normal-case">(optional)</span>
                    </label>
                    <input x-model="form.trip_context" type="text" maxlength="150"
                           placeholder="e.g. Kathmandu to Pokhara road trip"
                           class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]" />
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-2 uppercase tracking-wide">Car <span class="text-red-500">*</span></label>
                    <div class="flex rounded-xl overflow-hidden border border-slate-200">
                        <button type="button"
                            @click="form.linked_to_bijulicar = true; form.external_car_name = ''"
                            :class="form.linked_to_bijulicar ? 'bg-slate-900 text-white font-black' : 'bg-white text-slate-500 hover:bg-slate-50'"
                            class="flex-1 py-2 text-xs font-semibold transition-all">
                            <i class="fa-solid fa-link mr-1"></i> BijuliCar Listing
                        </button>
                        <button type="button"
                            @click="form.linked_to_bijulicar = false; form.car_id = null; carSearchQuery = ''"
                            :class="!form.linked_to_bijulicar ? 'bg-slate-900 text-white font-black' : 'bg-white text-slate-500 hover:bg-slate-50'"
                            class="flex-1 py-2 text-xs font-semibold transition-all">
                            <i class="fa-solid fa-pen mr-1"></i> Other Car
                        </button>
                    </div>

                    <div x-show="form.linked_to_bijulicar" class="mt-2 relative">
                        <input id="fab-car-input" x-model="carSearchQuery"
                               @focus="initCarTypeahead()" type="text" autocomplete="off"
                               placeholder="Search cars on BijuliCar…"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]" />
                        <ul id="fab-car-suggestions"
                            class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg z-[9999] max-h-48 overflow-y-auto"></ul>
                        <div x-show="form.car_id" class="mt-2 flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                            <i class="fa-solid fa-circle-check text-green-600 text-xs"></i>
                            <span class="text-xs text-green-700 font-bold flex-1" x-text="selectedCarName"></span>
                            <button type="button" @click="form.car_id = null; selectedCarName = ''; carSearchQuery = ''" class="text-slate-400 hover:text-slate-600">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        </div>
                        <p x-show="fieldErrors.car_id" class="text-red-500 text-xs mt-1" x-text="fieldErrors.car_id"></p>
                    </div>

                    <div x-show="!form.linked_to_bijulicar" class="mt-2">
                        <input x-model="form.external_car_name" type="text" maxlength="100"
                               placeholder="e.g. Tata Nexon EV, Honda City 2023…"
                               class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]" />
                        <p x-show="fieldErrors.external_car_name" class="text-red-500 text-xs mt-1" x-text="fieldErrors.external_car_name"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-700 mb-1.5 uppercase tracking-wide">Your Experience <span class="text-red-500">*</span></label>
                    <textarea x-model="form.body" rows="6" maxlength="3000"
                              placeholder="Tell us about your experience with this car — performance, comfort, issues, highlights…"
                              class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl resize-none focus:outline-none focus:ring-2 focus:ring-[#4ade80]/40 focus:border-[#4ade80]"></textarea>
                    <div class="flex justify-between mt-1">
                        <p x-show="fieldErrors.body" class="text-red-500 text-xs" x-text="fieldErrors.body"></p>
                        <p class="text-xs text-slate-400 ml-auto" x-text="form.body.length + ' / 3000'"></p>
                    </div>
                </div>

                <button type="submit" :disabled="formSubmitting"
                    class="w-full bg-[#4ade80] text-black font-black py-3 rounded-xl text-sm
                           hover:bg-[#22c55e] active:scale-95 transition-all
                           disabled:opacity-60 disabled:cursor-not-allowed">
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
document.addEventListener('alpine:init', () => {

// ── Main FAB component ────────────────────────────────────────────────────
Alpine.data('experienceFab', () => ({
        open: false,
        tab:  'feed',

        experiences:  [],
        feedLoading:  false,
        feedPage:     1,
        hasMore:      false,
        feedFilter:   { search: '', type: '' },

        carSearchQuery:    '',
        selectedCarName:   '',
        allCars:           [],
        carTypeaheadReady: false,

        form: {
            title:               '',
            trip_context:        '',
            body:                '',
            experience_type:     'general',
            linked_to_bijulicar: false,
            car_id:              null,
            external_car_name:   '',
        },
        fieldErrors:    {},
        formError:      '',
        formSuccess:    '',
        formSubmitting: false,

        init() {
            this.loadFeed();
            this._cycleFabLabel();

            // Allow other pages to link directly into the Share tab,
            // e.g. <a href="{{ route('marketplace') }}#share-experience">
            if (window.location.hash === '#share-experience') {
                this.tab = 'share';
                this.open = true;
                document.body.style.overflow = 'hidden';
            }
        },

        _cycleFabLabel() {
            const label = document.getElementById('fab-label');
            if (!label) return;

            const show = () => {
                // Slide up + fade in
                label.style.opacity   = '1';
                label.style.transform = 'translateY(0)';

                // After 4s — fade out + slide down
                setTimeout(() => {
                    label.style.opacity   = '0';
                    label.style.transform = 'translateY(6px)';

                    // After 5s wait — show again
                    setTimeout(show, 5000);
                }, 4000);
            };

            // Small initial delay so it doesn't pop instantly on page load
            setTimeout(show, 1200);
        },

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

        async loadFeed() {
            this.feedLoading = true;
            this.experiences = [];
            const params = new URLSearchParams({ page: this.feedPage });
            if (this.feedFilter.search) params.set('search', this.feedFilter.search);
            if (this.feedFilter.type)   params.set('type',   this.feedFilter.type);
            try {
                const res        = await fetch(`/experiences?${params}`);
                const data       = await res.json();
                this.experiences = data.data ?? [];
                this.hasMore     = !!data.next_page_url;
            } catch (e) {
                console.error('Experience feed error:', e);
            } finally {
                this.feedLoading = false;
            }
        },

        async initCarTypeahead() {
            if (this.carTypeaheadReady) return;
            try {
                const res    = await fetch('/experiences/cars/all');
                this.allCars = await res.json();
            } catch (e) {
                this.allCars = [];
            }
            this._wireTypeahead();
            this.carTypeaheadReady = true;
        },

        _wireTypeahead() {
            const input = document.getElementById('fab-car-input');
            const list  = document.getElementById('fab-car-suggestions');
            if (!input || !list) return;
            const render = (matches) => {
                list.innerHTML = '';
                if (!matches.length) { list.classList.add('hidden'); return; }
                matches.forEach(car => {
                    const li = document.createElement('li');
                    li.className = 'px-3 py-2.5 cursor-pointer text-xs font-bold text-slate-800 hover:bg-[#4ade80]/10 hover:text-[#16a34a] transition-colors border-b border-slate-50 last:border-0';
                    li.textContent = car.name;
                    li.addEventListener('mousedown', (e) => {
                        e.preventDefault();
                        this.form.car_id     = car.id;
                        this.selectedCarName = car.name;
                        this.carSearchQuery  = '';
                        input.value          = '';
                        list.classList.add('hidden');
                    });
                    list.appendChild(li);
                });
                list.classList.remove('hidden');
            };
            input.addEventListener('input', () => {
                this.carSearchQuery = input.value;
                const q = input.value.trim().toLowerCase();
                render(q ? this.allCars.filter(c => c.name.toLowerCase().includes(q)).slice(0,10) : this.allCars.slice(0,10));
            });
            input.addEventListener('focus', () => {
                const q = input.value.trim().toLowerCase();
                render(q ? this.allCars.filter(c => c.name.toLowerCase().includes(q)).slice(0,10) : this.allCars.slice(0,10));
            });
            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !list.contains(e.target)) list.classList.add('hidden');
            });
        },

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
                    this.formSuccess = data.message;
                    this.resetForm();
                } else if (res.status === 422) {
                    const errors     = data.errors ?? {};
                    this.fieldErrors = Object.fromEntries(Object.entries(errors).map(([k,v]) => [k, Array.isArray(v) ? v[0] : v]));
                    this.formError   = data.message ?? 'Please fix the errors below.';
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
            this.form           = { title:'', trip_context:'', body:'', experience_type:'general', linked_to_bijulicar:false, car_id:null, external_car_name:'' };
            this.carSearchQuery = '';
            this.selectedCarName = '';
            this.fieldErrors    = {};
            this.formError      = '';
        },

        formatDate(dateStr) {
            if (!dateStr) return '';
            return new Date(dateStr).toLocaleDateString('en-US', { day:'numeric', month:'short', year:'numeric' });
        },
        typeBadge(type) {
            const map = { rental:'bg-blue-100 text-blue-700', purchase:'bg-green-100 text-green-700', general:'bg-slate-100 text-slate-600' };
            return map[type] ?? 'bg-slate-100 text-slate-600';
        },
        typeLabel(type) {
            const map = { rental:'Rental', purchase:'Ride', general:'General' };
            return map[type] ?? type;
        },
}));

}); // end alpine:init
</script>