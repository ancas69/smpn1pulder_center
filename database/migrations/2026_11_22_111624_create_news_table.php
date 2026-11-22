<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();

            // Relasi ke penulis (user)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('judul');
            $table->string('slug')->unique();
            $table->enum('kategori', ['pengumuman', 'prestasi', 'kegiatan', 'akademik'])
                  ->default('pengumuman');
            $table->enum('status', ['draft', 'published', 'archived'])
                  ->default('draft');

            $table->text('ringkasan')->nullable();
            $table->longText('isi')->nullable();           // HTML dari rich text editor
            $table->string('thumbnail')->nullable();       // path gambar utama

            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();                          // opsional: soft delete

            $table->index(['status', 'published_at']);
            $table->index('kategori');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};