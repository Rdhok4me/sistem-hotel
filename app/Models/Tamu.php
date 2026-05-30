<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tamu extends Model
{
    use HasFactory;

    protected $table = 'tamu';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_telepon',
        'email',
        'alamat',
        'kota_asal',
        'pekerjaan',
        'jenis_identitas',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class);
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getJenisKelaminLengkapAttribute(): string
    {
        return $this->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    public function getInisialAttribute(): string
    {
        $parts = explode(' ', $this->nama_lengkap);
        $inisial = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $inisial .= strtoupper($part[0] ?? '');
        }
        return $inisial;
    }
}
