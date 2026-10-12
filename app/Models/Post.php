<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    // Mass-assignment protection: hanya kolom ini yang boleh diisi massal.
    protected $fillable = ['title', 'body', 'image'];

    protected static function booted(): void
    {
        // Hapus permanen -> file gambarnya ikut dihapus. (Soft delete TIDAK menghapus file,
        // karena post masih bisa dipulihkan dari trash.)
        static::forceDeleted(function (Post $post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
        });
    }

    /**
     * Local scope pencarian: Post::search('laravel')->get()
     * Dibungkus where(function) agar OR tidak merusak kondisi lain (mis. soft delete).
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        return $query->when($term, function (Builder $query, string $term) {
            $query->where(function (Builder $query) use ($term) {
                $query->where('title', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%");
            });
        });
    }

    /**
     * Accessor: $post->image_url
     * Memakai asset() agar URL mengikuti host yang sedang dipakai (127.0.0.1:8000 / virtual host),
     * bukan APP_URL.
     */
    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? asset('storage/'.$this->image) : null,
        );
    }
}
