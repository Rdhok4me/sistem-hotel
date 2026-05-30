<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_reservasi', 25)->unique();  // RSV-20240101-001
            $table->foreignId('tamu_id')
                  ->constrained('tamu')
                  ->restrictOnDelete();
            $table->foreignId('kamar_id')
                  ->constrained('kamar')
                  ->restrictOnDelete();
            $table->foreignId('user_id')                     // resepsionis yang input
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->date('tanggal_checkin');
            $table->date('tanggal_checkout');
            $table->unsignedInteger('jumlah_malam');
            $table->unsignedInteger('jumlah_tamu')->default(1);
            $table->decimal('harga_per_malam', 12, 2);
            $table->decimal('total_harga', 14, 2);
            $table->decimal('biaya_tambahan', 12, 2)->default(0);
            $table->enum('status', [
                'pending',       // baru dibuat, belum dikonfirmasi
                'konfirmasi',    // sudah dikonfirmasi
                'checkin',       // tamu sedang menginap
                'checkout',      // tamu sudah keluar
                'batal',         // reservasi dibatalkan
            ])->default('pending');
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index untuk query yang sering
            $table->index(['status', 'tanggal_checkin']);
            $table->index('tanggal_checkin');
            $table->index('tanggal_checkout');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
