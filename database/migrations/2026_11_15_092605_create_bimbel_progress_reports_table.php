<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbel_progress_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbel_class_id')->constrained('bimbel_classes')->onDelete('cascade');
            $table->foreignId('bimbel_session_id')->nullable()->constrained('bimbel_sessions')->onDelete('set null');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade');
            $table->decimal('nilai_evaluasi', 5, 2)->nullable();
            $table->text('catatan_perkembangan');
            $table->text('catatan_rekomendasi')->nullable();
            $table->boolean('wa_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbel_progress_reports');
    }
};
