<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice')->unique();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('bimbel_class_id')->nullable()->constrained('bimbel_classes')->onDelete('set null');
            $table->decimal('total_bayar', 12, 2);
            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran', 20)->default('unpaid');
            $table->string('snap_token')->nullable();
            $table->text('checkout_url')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->boolean('wa_notified')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
