<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'tipe_kamar_id',
        'nomor_kamar',
        'lantai',
        'status',
        'keterangan',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function tipeKamar()
    {
        return $this->belongsTo(TipeKamar::class);
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeTerisi($query)
    {
        return $query->where('status', 'terisi');
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'tersedia'    => ['class' => 'bg-emerald-100 text-emerald-800', 'label' => 'Tersedia'],
            'terisi'      => ['class' => 'bg-blue-100 text-blue-800',       'label' => 'Terisi'],
            'dibersihkan' => ['class' => 'bg-amber-100 text-amber-800',     'label' => 'Dibersihkan'],
            'maintenance' => ['class' => 'bg-red-100 text-red-800',         'label' => 'Maintenance'],
            default       => ['class' => 'bg-gray-100 text-gray-600',       'label' => ucfirst($this->status)],
        };
    }
}
