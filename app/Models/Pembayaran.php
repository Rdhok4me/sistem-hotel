<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';

    protected $fillable = [
        'reservasi_id',
        'kode_pembayaran',
        'jumlah_bayar',
        'jumlah_dp',
        'sisa_pembayaran',
        'metode_bayar',
        'status_bayar',
        'waktu_bayar',
        'user_id',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_bayar'     => 'decimal:2',
        'jumlah_dp'        => 'decimal:2',
        'sisa_pembayaran'  => 'decimal:2',
        'waktu_bayar'      => 'datetime',
    ];

    // ── Auto-generate kode pembayaran ─────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Pembayaran $model) {
            if (empty($model->kode_pembayaran)) {
                $today  = now()->format('Ymd');
                $urutan = static::whereDate('created_at', today())->count() + 1;
                $model->kode_pembayaran = 'PAY-' . $today . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relasi ───────────────────────────────────────────────

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getMetodeBayarLabelAttribute(): string
    {
        return match ($this->metode_bayar) {
            'tunai'         => 'Tunai',
            'transfer_bank' => 'Transfer Bank',
            'kartu_kredit'  => 'Kartu Kredit',
            'kartu_debit'   => 'Kartu Debit',
            'qris'          => 'QRIS',
            default         => ucfirst($this->metode_bayar),
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status_bayar) {
            'lunas'      => ['class' => 'bg-emerald-100 text-emerald-800', 'label' => 'Lunas'],
            'dp'         => ['class' => 'bg-amber-100 text-amber-800',     'label' => 'DP'],
            'belum_bayar'=> ['class' => 'bg-red-100 text-red-700',         'label' => 'Belum Bayar'],
            default      => ['class' => 'bg-gray-100 text-gray-600',       'label' => '-'],
        };
    }
}
