@extends('admin.layout')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Send Newsletter</h1>

        <form action="{{ route('admin.newsletter.send') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block font-bold text-sm mb-2">Subject</label>
                <input type="text" name="subject" class="w-full border px-3 py-2 rounded" required>
            </div>

            <div class="mb-4">
                <label class="block font-bold text-sm mb-2">Content (HTML allowed)</label>
                <textarea name="body" rows="6" class="w-full border px-3 py-2 rounded" required></textarea>
            </div>

            <button type="submit" class="px-6 py-2 bg-green-600 text-white font-bold rounded hover:bg-green-700">Send Newsletter</button>
        </form>
    </div>
@endsection
