<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipe_kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama');                          // Standard, Deluxe, Suite
            $table->decimal('harga_per_malam', 12, 2);      // harga dalam Rupiah
            $table->integer('kapasitas')->default(2);        // kapasitas tamu
            $table->text('fasilitas')->nullable();           // deskripsi fasilitas
            $table->string('foto')->nullable();              // path foto utama
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipe_kamar');
    }
};
