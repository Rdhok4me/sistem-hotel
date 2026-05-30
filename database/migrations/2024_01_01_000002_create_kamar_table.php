<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipe_kamar_id')
                  ->constrained('tipe_kamar')
                  ->cascadeOnDelete();
            $table->string('nomor_kamar', 10)->unique();
            $table->integer('lantai');
            $table->enum('status', [
                'tersedia',     // siap dipesan
                'terisi',       // sedang ditempati tamu
                'dibersihkan',  // sedang dibersihkan housekeeping
                'maintenance',  // perbaikan, tidak bisa dipesan
            ])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
