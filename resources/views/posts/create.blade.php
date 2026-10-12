@extends('layouts.app')

@section('title', 'Tulis Post')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Tulis Post Baru</h1>

    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data"
          class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf

        @include('posts._form', ['post' => $post, 'submit' => 'Simpan Post'])
    </form>
@endsection
