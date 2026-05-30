<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservasi_id')
                  ->unique()
                  ->constrained('reservasi')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')                     // resepsionis yang proses
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->timestamp('waktu_checkin');
            $table->unsignedInteger('jumlah_tamu_aktual')->default(1);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
