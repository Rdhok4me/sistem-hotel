<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Relasi ───────────────────────────────────────────────

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class);
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class);
    }

    public function checkOuts()
    {
        return $this->hasMany(CheckOut::class);
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class);
    }

    // ── Helper ───────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isResepsionis(): bool
    {
        return $this->hasRole('resepsionis');
    }

    public function getRoleNamaAttribute(): string
    {
        return $this->getRoleNames()->first() ?? '-';
    }
}
