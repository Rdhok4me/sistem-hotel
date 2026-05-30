<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservasi';

    protected $fillable = [
        'kode_reservasi',
        'tamu_id',
        'kamar_id',
        'user_id',
        'tanggal_checkin',
        'tanggal_checkout',
        'jumlah_malam',
        'jumlah_tamu',
        'harga_per_malam',
        'total_harga',
        'biaya_tambahan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_checkin'  => 'date',
        'tanggal_checkout' => 'date',
        'harga_per_malam'  => 'decimal:2',
        'total_harga'      => 'decimal:2',
        'biaya_tambahan'   => 'decimal:2',
    ];

    // ── Auto-generate kode reservasi ─────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Reservasi $model) {
            if (empty($model->kode_reservasi)) {
                $today   = now()->format('Ymd');
                $urutan  = static::whereDate('created_at', today())->withTrashed()->count() + 1;
                $model->kode_reservasi = 'RSV-' . $today . '-' . str_pad($urutan, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    // ── Relasi ───────────────────────────────────────────────

    public function tamu()
    {
        return $this->belongsTo(Tamu::class);
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }

    public function checkOut()
    {
        return $this->hasOne(CheckOut::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeAktif($query)
    {
        return $query->whereNotIn('status', ['batal', 'checkout']);
    }

    public function scopeCheckinHariIni($query)
    {
        return $query->where('status', 'konfirmasi')
                     ->whereDate('tanggal_checkin', today());
    }

    public function scopeCheckoutHariIni($query)
    {
        return $query->where('status', 'checkin')
                     ->whereDate('tanggal_checkout', today());
    }

    // ── Accessor ─────────────────────────────────────────────

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending'    => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pending'],
            'konfirmasi' => ['class' => 'bg-blue-100 text-blue-800',     'label' => 'Konfirmasi'],
            'checkin'    => ['class' => 'bg-emerald-100 text-emerald-800','label' => 'Check-in'],
            'checkout'   => ['class' => 'bg-gray-100 text-gray-600',     'label' => 'Check-out'],
            'batal'      => ['class' => 'bg-red-100 text-red-700',       'label' => 'Batal'],
            default      => ['class' => 'bg-gray-100 text-gray-600',     'label' => ucfirst($this->status)],
        };
    }

    public function getTotalAkhirAttribute(): float
    {
        return (float) $this->total_harga + (float) ($this->checkOut?->biaya_tambahan ?? 0);
    }
}
