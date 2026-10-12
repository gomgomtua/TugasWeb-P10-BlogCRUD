<header class="border-b bg-white">
    <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-4">
        <a href="{{ route('posts.index') }}" class="text-lg font-bold text-indigo-600"><img src="{{ asset('images/logo.png') }}" alt="img"> {{ config('app.name') }}</a>

        <ul class="flex items-center gap-1 text-sm font-medium">
            <li>
                <a href="{{ route('posts.index') }}"
                   class="rounded-md px-3 py-2 {{ request()->routeIs('posts.index', 'posts.show', 'posts.edit') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua Post
                </a>
            </li>
            <li>
                <a href="{{ route('posts.trash') }}"
                   class="rounded-md px-3 py-2 {{ request()->routeIs('posts.trash') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-100' }}">
                    Trash
                </a>
            </li>
            <li>
                <a href="{{ route('posts.create') }}" class="ml-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                    + Tulis Post
                </a>
            </li>
        </ul>
    </nav>
</header>
