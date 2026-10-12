<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('body');
            $table->string('image')->nullable();   // path relatif di disk "public"
            $table->timestamps();
            $table->softDeletes();                 // kolom deleted_at (bonus soft delete)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
