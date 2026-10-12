@extends('layouts.app')

@section('title', 'Semua Post')

@section('content')
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold">Semua Post</h1>

        {{-- Bonus: pencarian (GET, tanpa @csrf) --}}
        <form method="GET" action="{{ route('posts.index') }}" class="flex gap-2">
            <input type="search" name="q" value="{{ $q }}" placeholder="Cari judul atau isi…"
                   class="w-56 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
            <button type="submit" class="rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white hover:bg-slate-900">Cari</button>
            @if ($q)
                <a href="{{ route('posts.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm hover:bg-slate-100">Reset</a>
            @endif
        </form>
    </div>

    @if ($q)
        <p class="mb-4 text-sm text-slate-500">
            Hasil pencarian untuk “<strong>{{ $q }}</strong>”: {{ $posts->total() }} post ditemukan.
        </p>
    @endif

    @if ($posts->isNotEmpty())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <x-card :post="$post" />
            @endforeach
        </div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
            Belum ada post.
            <a href="{{ route('posts.create') }}" class="font-medium text-indigo-600 hover:underline">Tulis post pertama</a>
        </div>
    @endif

    {{-- Pagination otomatis (6 per halaman) --}}
    <div class="mt-8">{{ $posts->links() }}</div>
@endsection
