<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bimbel_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bimbel_session_id')->constrained('bimbel_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('status', 20)->default('hadir');
            $table->string('catatan')->nullable();
            $table->boolean('wa_sent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bimbel_attendances');
    }
};
