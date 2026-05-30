<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                  ->unique()
                  ->constrained('reservasi')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')                     // resepsionis yang proses
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->timestamp('waktu_checkout');
            $table->decimal('biaya_tambahan', 12, 2)->default(0);
            $table->text('keterangan_biaya')->nullable();
            $table->decimal('total_akhir', 14, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_outs');
    }
};
