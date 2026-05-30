<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipeKamar extends Model
{
    use HasFactory;

    protected $table = 'tipe_kamar';

    protected $fillable = [
        'nama',
        'harga_per_malam',
        'kapasitas',
        'fasilitas',
        'foto',
    ];

    protected $casts = [
        'harga_per_malam' => 'decimal:2',
        'kapasitas'        => 'integer',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function kamar()
    {
        return $this->hasMany(Kamar::class);
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_malam, 0, ',', '.');
    }

    public function getFasilitasListAttribute(): array
    {
        return array_map('trim', explode(',', $this->fasilitas ?? ''));
    }
}
