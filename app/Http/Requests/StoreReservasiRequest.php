<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tamu_id'          => ['required', 'exists:tamu,id'],
            'kamar_id'         => ['required', 'exists:kamar,id'],
            'tanggal_checkin'  => ['required', 'date', 'after_or_equal:today'],
            'tanggal_checkout' => ['required', 'date', 'after:tanggal_checkin'],
            'jumlah_tamu'      => ['required', 'integer', 'min:1', 'max:10'],
            'catatan'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'tamu_id.required'          => 'Data tamu wajib dipilih.',
            'tamu_id.exists'            => 'Tamu tidak ditemukan.',
            'kamar_id.required'         => 'Kamar wajib dipilih.',
            'kamar_id.exists'           => 'Kamar tidak ditemukan.',
            'tanggal_checkin.required'  => 'Tanggal check-in wajib diisi.',
            'tanggal_checkin.after_or_equal' => 'Tanggal check-in tidak boleh kurang dari hari ini.',
            'tanggal_checkout.required' => 'Tanggal check-out wajib diisi.',
            'tanggal_checkout.after'    => 'Tanggal check-out harus setelah tanggal check-in.',
            'jumlah_tamu.required'      => 'Jumlah tamu wajib diisi.',
            'jumlah_tamu.min'           => 'Jumlah tamu minimal 1 orang.',
            'jumlah_tamu.max'           => 'Jumlah tamu maksimal 10 orang.',
        ];
    }
}
