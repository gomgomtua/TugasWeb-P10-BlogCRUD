<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Blog') · {{ config('app.name') }}</title>

    {{-- Tailwind via CDN: tidak perlu `npm run dev` / Vite untuk menjalankan tugas ini --}}
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    @include('partials.navbar')

    <main class="mx-auto max-w-5xl px-4 py-8">
        {{-- Flash message: sukses / gagal / ringkasan error validasi --}}
        @if (session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert type="danger">{{ session('error') }}</x-alert>
        @endif

        @if ($errors->any())
            <x-alert type="danger">
                <strong>Gagal menyimpan.</strong> Periksa kembali {{ $errors->count() }} isian yang bermasalah di bawah ini.
            </x-alert>
        @endif

        @yield('content')
    </main>

    <footer class="mt-8 border-t bg-white py-6 text-center text-sm text-slate-500">
        Tugas Rutin 10 — Blog CRUD Laravel · Pemrograman Web · FMIPA UNIMED
    </footer>

    @stack('scripts')
</body>
</html>
