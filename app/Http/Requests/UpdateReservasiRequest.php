<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservasiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'tanggal_checkin'  => ['required', 'date'],
            'tanggal_checkout' => ['required', 'date', 'after:tanggal_checkin'],
            'jumlah_tamu'      => ['required', 'integer', 'min:1', 'max:10'],
            'catatan'          => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_checkin.required'  => 'Tanggal check-in wajib diisi.',
            'tanggal_checkout.required' => 'Tanggal check-out wajib diisi.',
            'tanggal_checkout.after'    => 'Tanggal check-out harus setelah tanggal check-in.',
        ];
    }
}
