<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckOut extends Model
{
    use HasFactory;

    protected $table = 'check_outs';

    protected $fillable = [
        'reservasi_id',
        'user_id',
        'waktu_checkout',
        'biaya_tambahan',
        'keterangan_biaya',
        'total_akhir',
        'catatan',
    ];

    protected $casts = [
        'waktu_checkout'  => 'datetime',
        'biaya_tambahan'  => 'decimal:2',
        'total_akhir'     => 'decimal:2',
    ];

    // ── Relasi ───────────────────────────────────────────────

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
