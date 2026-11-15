<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbel_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbel_class_id')->constrained('bimbel_classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('status_pembayaran', 20)->default('pending');
            $table->dateTime('tanggal_daftar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbel_enrollments');
    }
};
