<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostCrudTest extends TestCase
{
    use RefreshDatabase;

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Belajar Laravel Itu Menyenangkan',
            'body'  => 'Laravel memisahkan Model, View, dan Controller dengan rapi.',
        ], $overrides);
    }

    // ---------------------------------------------------------------- READ

    public function test_root_redirects_to_posts_index(): void
    {
        $this->get('/')->assertRedirect(route('posts.index'));
    }

    public function test_index_is_paginated_six_per_page(): void
    {
        Post::factory()->count(8)->create();

        $this->get(route('posts.index'))
            ->assertOk()
            ->assertViewHas('posts', fn ($posts) => $posts->count() === 6 && $posts->total() === 8);

        $this->get(route('posts.index', ['page' => 2]))
            ->assertViewHas('posts', fn ($posts) => $posts->count() === 2);
    }

    public function test_index_can_search_by_title_or_body(): void
    {
        Post::factory()->create(['title' => 'Panduan Eloquent', 'body' => 'Isi tentang ORM yang panjang sekali.']);
        Post::factory()->create(['title' => 'Resep Nasi Goreng', 'body' => 'Bumbu dan cara memasak yang enak.']);

        $this->get(route('posts.index', ['q' => 'Eloquent']))
            ->assertOk()
            ->assertSee('Panduan Eloquent')
            ->assertDontSee('Resep Nasi Goreng');

        $this->get(route('posts.index', ['q' => 'memasak']))
            ->assertSee('Resep Nasi Goreng')
            ->assertDontSee('Panduan Eloquent');
    }

    public function test_show_uses_route_model_binding_and_returns_404_when_missing(): void
    {
        $post = Post::factory()->create(['title' => 'Judul Detail']);

        $this->get(route('posts.show', $post))->assertOk()->assertSee('Judul Detail');
        $this->get(route('posts.show', 999999))->assertNotFound();
    }

    // -------------------------------------------------- FORMS: CSRF & SPOOFING

    public function test_forms_contain_csrf_token_and_method_spoofing(): void
    {
        $post = Post::factory()->create();

        $this->get(route('posts.create'))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('enctype="multipart/form-data"', false);

        $this->get(route('posts.edit', $post))
            ->assertOk()
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method" value="PUT"', false);

        $this->get(route('posts.show', $post))
            ->assertSee('name="_method" value="DELETE"', false);
    }

    // ------------------------------------------------------------- CREATE

    public function test_store_validates_required_fields(): void
    {
        $this->post(route('posts.store'), [])
            ->assertSessionHasErrors(['title', 'body']);

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_store_keeps_old_input_when_validation_fails(): void
    {
        $this->from(route('posts.create'))
            ->post(route('posts.store'), ['title' => 'Judul saja', 'body' => ''])
            ->assertRedirect(route('posts.create'))
            ->assertSessionHasErrors('body')
            ->assertSessionHasInput('title', 'Judul saja');
    }

    public function test_store_creates_post_and_flashes_success(): void
    {
        $this->post(route('posts.store'), $this->validData())
            ->assertRedirect(route('posts.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('posts', ['title' => 'Belajar Laravel Itu Menyenangkan']);
    }

    public function test_store_rejects_non_image_upload(): void
    {
        Storage::fake('public');

        $this->post(route('posts.store'), $this->validData([
            'image' => UploadedFile::fake()->create('dokumen.pdf', 20, 'application/pdf'),
        ]))->assertSessionHasErrors('image');

        $this->assertDatabaseCount('posts', 0);
    }

    public function test_store_saves_uploaded_image(): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('Ekstensi PHP GD tidak aktif.');
        }

        Storage::fake('public');

        $this->post(route('posts.store'), $this->validData([
            'image' => UploadedFile::fake()->image('cover.jpg', 600, 400),
        ]))->assertRedirect(route('posts.index'));

        $post = Post::firstOrFail();
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    // ------------------------------------------------------------- UPDATE

    public function test_update_changes_post_and_flashes_success(): void
    {
        $post = Post::factory()->create();

        $this->put(route('posts.update', $post), $this->validData(['title' => 'Judul Baru']))
            ->assertRedirect(route('posts.show', $post))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Judul Baru']);
    }

    public function test_update_validates_input(): void
    {
        $post = Post::factory()->create(['title' => 'Judul Lama']);

        $this->put(route('posts.update', $post), $this->validData(['title' => '']))
            ->assertSessionHasErrors('title');

        $this->assertDatabaseHas('posts', ['id' => $post->id, 'title' => 'Judul Lama']);
    }

    // ------------------------------------------------- DELETE / SOFT DELETE

    public function test_destroy_soft_deletes_post(): void
    {
        $post = Post::factory()->create();

        $this->delete(route('posts.destroy', $post))
            ->assertRedirect(route('posts.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($post);
        $this->get(route('posts.show', $post))->assertNotFound();
    }

    public function test_trashed_post_can_be_restored(): void
    {
        $post = Post::factory()->create(['title' => 'Post Di Trash']);
        $post->delete();

        $this->get(route('posts.trash'))->assertOk()->assertSee('Post Di Trash');

        $this->patch(route('posts.restore', $post))
            ->assertRedirect(route('posts.trash'))
            ->assertSessionHas('success');

        $this->assertNotSoftDeleted($post);
    }

    public function test_trashed_post_can_be_force_deleted(): void
    {
        $post = Post::factory()->create();
        $post->delete();

        $this->delete(route('posts.force-destroy', $post))
            ->assertRedirect(route('posts.trash'));

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
