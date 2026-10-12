{{-- Partial form yang dipakai bersama oleh create & edit.
     Variabel: $post (Post kosong saat create), $submit (teks tombol) --}}

<div>
    <label for="title" class="block text-sm font-medium">Judul</label>
    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}"
           class="mt-1 w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-1 {{ $errors->has('title') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">
    @error('title')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div>
    <label for="body" class="block text-sm font-medium">Isi post</label>
    <textarea id="body" name="body" rows="9"
              class="mt-1 w-full rounded-lg border px-3 py-2 focus:outline-none focus:ring-1 {{ $errors->has('body') ? 'border-red-400 focus:border-red-500 focus:ring-red-500' : 'border-slate-300 focus:border-indigo-500 focus:ring-indigo-500' }}">{{ old('body', $post->body) }}</textarea>
    @error('body')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

{{-- Bonus: upload gambar (form harus enctype="multipart/form-data") --}}
<div>
    <label for="image" class="block text-sm font-medium">Gambar sampul <span class="font-normal text-slate-400">(opsional · jpg/png/webp · maks 2 MB)</span></label>
    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
           class="mt-1 block w-full text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:font-medium file:text-indigo-700 hover:file:bg-indigo-100">
    @error('image')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror

    <img id="image-preview" src="{{ $post->image_url }}" alt="Pratinjau gambar"
         class="mt-3 max-h-48 rounded-lg border {{ $post->image_url ? '' : 'hidden' }}">
</div>

<div class="flex items-center gap-3 pt-2">
    <button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 font-medium text-white hover:bg-indigo-700">{{ $submit }}</button>
    <a href="{{ route('posts.index') }}" class="text-sm text-slate-600 hover:underline">Batal</a>
</div>

@push('scripts')
    <script>
        // Pratinjau gambar sebelum diunggah
        document.getElementById('image')?.addEventListener('change', function (event) {
            const file = event.target.files[0];
            const preview = document.getElementById('image-preview');
            if (!file || !preview) return;
            preview.src = URL.createObjectURL(file);
            preview.classList.remove('hidden');
        });
    </script>
@endpush
