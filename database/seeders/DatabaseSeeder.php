<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 25 post -> dengan 6 per halaman = 5 halaman pagination.
        Post::factory()->count(25)->create();
    }
}
