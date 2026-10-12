<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Throwable;

class PostController extends Controller
{
    /** GET /posts — daftar + pencarian + pagination */
    public function index(Request $request): View
    {
        $q = $request->query('q');

        $posts = Post::query()
            ->search($q)
            ->latest()
            ->paginate(6)
            ->withQueryString(); // pagination tetap membawa ?q=...

        return view('posts.index', compact('posts', 'q'));
    }

    /** GET /posts/create — form tambah */
    public function create(): View
    {
        return view('posts.create', ['post' => new Post]);
    }

    /** POST /posts — validasi (PostRequest) + simpan + flash */
    public function store(PostRequest $request): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            Post::create($data);
        } catch (Throwable $e) {
            return $this->failed($e, 'Gagal menyimpan post. Silakan coba lagi.');
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post berhasil dibuat!');
    }

    /** GET /posts/{post} — Route Model Binding: tidak ketemu → otomatis 404 */
    public function show(Post $post): View
    {
        return view('posts.show', compact('post'));
    }

    /** GET /posts/{post}/edit */
    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    /** PUT/PATCH /posts/{post} */
    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        try {
            $data = $request->safe()->except('image');
            $oldImage = $post->image;

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('posts', 'public');
            }

            $post->update($data);

            // Hapus gambar lama hanya setelah update berhasil.
            if ($oldImage && isset($data['image'])) {
                Storage::disk('public')->delete($oldImage);
            }
        } catch (Throwable $e) {
            return $this->failed($e, 'Gagal memperbarui post. Silakan coba lagi.');
        }

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Post berhasil diperbarui!');
    }

    /** DELETE /posts/{post} — soft delete (masuk trash) */
    public function destroy(Post $post): RedirectResponse
    {
        try {
            $post->delete();
        } catch (Throwable $e) {
            return $this->failed($e, 'Gagal menghapus post.');
        }

        return redirect()
            ->route('posts.index')
            ->with('success', 'Post dipindahkan ke trash. Kamu masih bisa memulihkannya.');
    }

    /** GET /posts/trash */
    public function trash(): View
    {
        $posts = Post::onlyTrashed()
            ->latest('deleted_at')
            ->paginate(6);

        return view('posts.trash', compact('posts'));
    }

    /** PATCH /posts/{post}/restore */
    public function restore(Post $post): RedirectResponse
    {
        $post->restore();

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post berhasil dipulihkan.');
    }

    /** DELETE /posts/{post}/force — hapus permanen (+ file gambar via event forceDeleted) */
    public function forceDestroy(Post $post): RedirectResponse
    {
        try {
            $post->forceDelete();
        } catch (Throwable $e) {
            return $this->failed($e, 'Gagal menghapus permanen post.');
        }

        return redirect()
            ->route('posts.trash')
            ->with('success', 'Post dihapus permanen.');
    }

    /** Flash pesan GAGAL: catat error ke log, kembali ke form dengan input lama. */
    private function failed(Throwable $e, string $message): RedirectResponse
    {
        report($e);

        return back()->withInput()->with('error', $message);
    }
}
