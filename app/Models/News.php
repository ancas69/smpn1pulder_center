<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'judul',
        'slug',
        'kategori',
        'status',
        'ringkasan',
        'isi',
        'thumbnail',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /* ---------- Relasi ---------- */

    // Berita milik satu penulis (user)
    public function penulis()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /* ---------- Boot: auto slug ---------- */

    protected static function booted(): void
    {
        static::creating(function (News $berita) {
            if (empty($berita->slug)) {
                $berita->slug = static::generateUniqueSlug($berita->judul);
            }
            if ($berita->status === 'published' && empty($berita->published_at)) {
                $berita->published_at = now();
            }
        });

        static::updating(function (News $berita) {
            if ($berita->isDirty('judul') && empty($berita->slug)) {
                $berita->slug = static::generateUniqueSlug($berita->judul);
            }
            if ($berita->isDirty('status')
                && $berita->status === 'published'
                && empty($berita->published_at)) {
                $berita->published_at = now();
            }
        });
    }

    public static function generateUniqueSlug(string $judul): string
    {
        $base = Str::slug($judul);
        $slug = $base;
        $i = 2;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}