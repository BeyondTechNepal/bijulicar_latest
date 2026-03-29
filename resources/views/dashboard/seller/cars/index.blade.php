@extends($layout)
@section('title', 'My Listings')
@section('page-title', 'My Listings')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ ucfirst($prefix) }} Portal</p>
            <p class="text-sm font-bold text-slate-600 mt-0.5">All vehicles you have listed on BijuliCar.</p>
        </div>
        <a href="{{ route($prefix . '.cars.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl text-[11px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            + New Listing
        </a>
    </div>

    @if($cars->isNotEmpty())
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Table header --}}
        <div class="grid grid-cols-12 gap-4 px-6 py-3 border-b border-slate-100 bg-slate-50">
            <div class="col-span-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Vehicle</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Price</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</div>
            <div class="col-span-2 text-[10px] font-black text-slate-400 uppercase tracking-widest">Orders</div>
            <div class="col-span-1"></div>
        </div>

        @foreach($cars as $car)
        <div class="grid grid-cols-12 gap-4 px-6 py-4 border-b border-slate-100 last:border-0 items-center hover:bg-slate-50/50 transition-colors">

            {{-- Vehicle --}}
            <div class="col-span-5 flex items-center gap-3">
                {{-- Thumbnail --}}
                <div class="w-12 h-12 bg-slate-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                    @if($car->primaryImage)
                        <img src="{{ $car->primaryImage->url() }}" class="w-full h-full object-cover" alt="{{ $car->displayName() }}">
                    @else
                        <span class="text-lg opacity-30">⚡</span>
                    @endif
                </div>
                <div>
                    <p class="text-sm font-black text-slate-900 uppercase italic tracking-tight leading-tight">{{ $car->displayName() }}</p>
                    <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                        {{ ucfirst($car->drivetrain) }} · {{ ucfirst($car->condition) }} · {{ $car->location }}
                    </p>
                </div>
            </div>

            {{-- Price --}}
            <div class="col-span-2">
                <p class="text-sm font-black text-slate-800">{{ $car->formattedPrice() }}</p>
                @if($car->price_negotiable)
                    <p class="text-[10px] font-black text-[#16a34a] uppercase tracking-widest mt-0.5">Negotiable</p>
                @endif
            </div>

            {{-- Status --}}
            <div class="col-span-2">
                <span @class([
                    'text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider',
                    'bg-[#4ade80]/15 text-[#16a34a]' => $car->status === 'available',
                    'bg-yellow-100 text-yellow-700'  => $car->status === 'reserved',
                    'bg-slate-100 text-slate-500'    => $car->status === 'inactive',
                    'bg-blue-100 text-blue-700'      => $car->status === 'sold',
                ])>{{ ucfirst($car->status) }}</span>
            </div>

            {{-- Orders count --}}
            <div class="col-span-2">
                <p class="text-sm font-black text-slate-700">{{ $car->orders_count }}</p>
                <p class="text-[10px] text-slate-400 font-medium">order{{ $car->orders_count !== 1 ? 's' : '' }}</p>
            </div>

            {{-- Actions --}}
            <div class="col-span-1 flex justify-end items-center gap-1">
                <a href="{{ route($prefix . '.cars.edit', $car) }}"
                    class="w-8 h-8 bg-slate-100 hover:bg-slate-900 hover:text-white rounded-xl flex items-center justify-center transition-all group"
                    title="Edit">
                    <svg class="w-4 h-4 text-slate-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route($prefix . '.cars.destroy', $car) }}"
                    onsubmit="return confirm('Delete {{ $car->displayName() }}? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="w-8 h-8 bg-red-50 hover:bg-red-500 rounded-xl flex items-center justify-center transition-all group"
                        title="Delete">
                        <svg class="w-4 h-4 text-red-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>

        </div>
        @endforeach
    </div>

    @if($cars->hasPages())
    <div class="mt-5">{{ $cars->links() }}</div>
    @endif

    @else
    <div class="bg-white border border-dashed border-slate-200 rounded-2xl p-14 text-center">
        <p class="text-5xl mb-4">🚗</p>
        <p class="font-black text-slate-900 uppercase italic tracking-tight text-lg">No listings yet</p>
        <p class="text-sm text-slate-500 font-medium mt-2 mb-6">Create your first listing to start selling on BijuliCar.</p>
        <a href="{{ route($prefix . '.cars.create') }}"
            class="inline-flex items-center gap-2 bg-slate-900 text-white px-6 py-3 rounded-xl text-[12px] font-black uppercase italic tracking-widest hover:bg-[#16a34a] transition-all shadow-lg">
            + Create First Listing
        </a>
    </div>
    @endif

@endsection