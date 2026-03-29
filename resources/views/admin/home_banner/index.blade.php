<a href="{{ route('admin.home_banner.create') }}">Add Banner</a>

@foreach($banners as $banner)
    <div class="mb-6 p-4 border rounded-lg">
        <p>{{ $banner->title }} - {{ $banner->subtitle }}</p>

        @foreach(['image1','image2','image3'] as $img)
            @if($banner->$img)
                <img src="{{ asset('storage/' . $banner->$img) }}" width="150">
            @endif
        @endforeach

        <div class="flex gap-2 mt-2">
            <a href="{{ route('admin.home_banner.edit', $banner) }}">Edit</a>

            <form method="POST" action="{{ route('admin.home_banner.destroy', $banner) }}">
                @csrf
                @method('DELETE')
                <button>Delete</button>
            </form>
        </div>
    </div>
@endforeach