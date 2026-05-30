<?php

namespace App\Policies;

use App\Models\Reservasi;
use App\Models\User;

class ReservasiPolicy
{
    /**
     * Admin bisa edit semua reservasi.
     * Resepsionis hanya bisa edit reservasi yang dia buat sendiri.
     */
    public function update(User $user, Reservasi $reservasi): bool
    {
        if ($user->hasRole('admin')) return true;

        return $reservasi->user_id === $user->id
            && ! in_array($reservasi->status, ['checkin', 'checkout', 'batal']);
    }

    /**
     * Aturan hapus sama seperti update.
     */
    public function delete(User $user, Reservasi $reservasi): bool
    {
        if ($user->hasRole('admin')) return true;

        return $reservasi->user_id === $user->id
            && $reservasi->status !== 'checkin';
    }
}
