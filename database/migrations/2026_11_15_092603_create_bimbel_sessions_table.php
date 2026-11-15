<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbel_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbel_class_id')->constrained('bimbel_classes')->onDelete('cascade');
            $table->integer('pertemuan_ke');
            $table->dateTime('tanggal');
            $table->text('materi_pembahasan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbel_sessions');
    }
};
