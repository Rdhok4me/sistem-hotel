<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;

    protected $table = 'check_ins';

    protected $fillable = [
        'reservasi_id',
        'user_id',
        'waktu_checkin',
        'jumlah_tamu_aktual',
        'catatan',
    ];

    protected $casts = [
        'waktu_checkin' => 'datetime',
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
