@extends('layouts.app')

@section('title', 'Trash')

@section('content')
    <h1 class="mb-1 text-2xl font-bold">Trash</h1>
    <p class="mb-6 text-sm text-slate-500">Post yang dihapus (soft delete). Pulihkan atau hapus permanen.</p>

    @if ($posts->isNotEmpty())
        <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-slate-200">
            <ul class="divide-y divide-slate-100">
                @foreach ($posts as $post)
                    <li class="flex flex-col gap-3 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="font-medium">{{ $post->title }}</p>
                            <p class="text-xs text-slate-500">Dihapus {{ $post->deleted_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <form method="POST" action="{{ route('posts.restore', $post) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 font-medium hover:bg-slate-100">Pulihkan</button>
                            </form>

                            <form method="POST" action="{{ route('posts.force-destroy', $post) }}"
                                  onsubmit="return confirm('Hapus PERMANEN? Tindakan ini tidak bisa dibatalkan.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-lg bg-red-600 px-3 py-1.5 font-medium text-white hover:bg-red-700">Hapus permanen</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="mt-6">{{ $posts->links() }}</div>
    @else
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center text-slate-500">
            Trash kosong.
        </div>
    @endif
@endsection
