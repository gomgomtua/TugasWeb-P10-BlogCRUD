@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <h1 class="mb-6 text-2xl font-bold">Edit Post</h1>

    <form method="POST" action="{{ route('posts.update', $post) }}" enctype="multipart/form-data"
          class="space-y-5 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @method('PUT')

        @include('posts._form', ['post' => $post, 'submit' => 'Perbarui Post'])
    </form>
@endsection
