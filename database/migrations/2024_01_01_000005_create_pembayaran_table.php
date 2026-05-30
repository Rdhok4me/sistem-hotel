<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                  ->unique()                                 // 1 reservasi = 1 data pembayaran
                  ->constrained('reservasi')
                  ->cascadeOnDelete();
            $table->string('kode_pembayaran', 25)->unique(); // PAY-20240101-001
            $table->decimal('jumlah_bayar', 14, 2);
            $table->decimal('jumlah_dp', 12, 2)->default(0);
            $table->decimal('sisa_pembayaran', 12, 2)->default(0);
            $table->enum('metode_bayar', [
                'tunai',
                'transfer_bank',
                'kartu_kredit',
                'kartu_debit',
                'qris',
            ]);
            $table->enum('status_bayar', [
                'belum_bayar',
                'dp',
                'lunas',
            ])->default('belum_bayar');
            $table->timestamp('waktu_bayar')->nullable();
            $table->foreignId('user_id')                     // kasir yang proses
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
