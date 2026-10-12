@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        @if ($post->image_url)
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="max-h-96 w-full object-cover">
        @endif

        <div class="p-6 sm:p-8">
            <h1 class="text-3xl font-bold leading-tight">{{ $post->title }}</h1>
            <p class="mt-2 text-sm text-slate-500">
                Dibuat {{ $post->created_at->translatedFormat('d F Y, H:i') }}
                @if ($post->updated_at->gt($post->created_at))
                    · diperbarui {{ $post->updated_at->diffForHumans() }}
                @endif
            </p>

            {{-- e() meng-escape HTML (anti XSS) lalu nl2br mengubah baris baru menjadi <br> --}}
            <div class="mt-6 leading-relaxed text-slate-700">{!! nl2br(e($post->body)) !!}</div>

            <div class="mt-8 flex items-center gap-3 border-t pt-5 text-sm">
                <a href="{{ route('posts.index') }}" class="text-slate-600 hover:underline">← Kembali</a>
                <a href="{{ route('posts.edit', $post) }}" class="rounded-lg border border-slate-300 px-4 py-2 font-medium hover:bg-slate-100">Edit</a>

                <form method="POST" action="{{ route('posts.destroy', $post) }}"
                      onsubmit="return confirm('Pindahkan post ini ke trash?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </article>
@endsection
