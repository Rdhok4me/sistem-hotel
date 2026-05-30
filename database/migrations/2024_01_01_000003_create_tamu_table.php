<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tamu', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nik', 20)->unique();             // Nomor Induk Kependudukan
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_telepon', 20);
            $table->string('email')->nullable();
            $table->text('alamat')->nullable();
            $table->string('kota_asal')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->enum('jenis_identitas', [
                'ktp', 'sim', 'paspor'
            ])->default('ktp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};
