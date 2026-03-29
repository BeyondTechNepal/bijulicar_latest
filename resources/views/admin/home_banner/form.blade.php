<form method="POST"
      action="{{ isset($home_banner) ? route('admin.home_banner.update', $home_banner) : route('admin.home_banner.store') }}"
      enctype="multipart/form-data">

    @csrf
    @if(isset($home_banner)) @method('PUT') @endif

    <input type="text" name="title" placeholder="Title" value="{{ old('title', $home_banner->title ?? '') }}">
    <input type="text" name="subtitle" placeholder="Subtitle" value="{{ old('subtitle', $home_banner->subtitle ?? '') }}">

    @foreach(['image1','image2','image3'] as $img)
        <label>{{ strtoupper($img) }}</label>
        <input type="file" name="{{ $img }}">
        @if(isset($home_banner) && $home_banner->$img)
            <img src="{{ asset('storage/' . $home_banner->$img) }}" width="150">
        @endif
    @endforeach

    <button type="submit">{{ isset($home_banner) ? 'Update' : 'Create' }}</button>
</form>