<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /** "/" di proyek ini mengarahkan ke daftar post, jadi yang dites adalah /posts. */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/posts');

        $response->assertStatus(200);
    }
}
