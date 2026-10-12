@props(['post'])

<article class="flex flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
    @if ($post->image_url)
        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="h-44 w-full object-cover" loading="lazy">
    @else
        <div class="flex h-44 items-center justify-center bg-gradient-to-br from-indigo-100 to-sky-100 text-5xl"><img src="{{ asset('images/logo.png') }}" alt="img"></div>
    @endif

    <div class="flex flex-1 flex-col p-5">
        <h2 class="text-lg font-semibold leading-snug">
            <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">{{ $post->title }}</a>
        </h2>

        <p class="mt-1 text-xs text-slate-500">{{ $post->created_at->translatedFormat('d F Y, H:i') }}</p>

        <p class="mt-3 flex-1 text-sm text-slate-600">{{ str($post->body)->limit(120) }}</p>

        <div class="mt-4 flex items-center gap-3 text-sm">
            <a href="{{ route('posts.show', $post) }}" class="font-medium text-indigo-600 hover:underline">Baca</a>
            <a href="{{ route('posts.edit', $post) }}" class="font-medium text-slate-600 hover:underline">Edit</a>

            <form method="POST" action="{{ route('posts.destroy', $post) }}" class="ml-auto"
                  onsubmit="return confirm('Pindahkan post ini ke trash?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="font-medium text-red-600 hover:underline">Hapus</button>
            </form>
        </div>
    </div>
</article>
