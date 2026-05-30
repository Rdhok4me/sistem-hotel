<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreTamuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
    public function rules(): array
    {
        $tamuId = $this->route('tamu')?->id;
        $jenis  = $this->input('jenis_identitas');

        // Validasi NIK berbeda tergantung jenis identitas
        $nikRules = ['required', 'string', Rule::unique('tamu', 'nik')->ignore($tamuId)];

        if ($jenis === 'ktp' || $jenis === 'sim') {
            // KTP dan SIM harus 16 digit angka
            $nikRules[] = 'digits:16';
        } elseif ($jenis === 'paspor') {
            // Paspor: campuran huruf dan angka, 6-9 karakter
            $nikRules[] = 'min:6';
            $nikRules[] = 'max:20';
            $nikRules[] = 'regex:/^[A-Za-z0-9]+$/';
        }

        return [
            'nama_lengkap'    => ['required', 'string', 'max:100'],
            'jenis_identitas' => ['required', 'in:ktp,sim,paspor'],
            'nik'             => $nikRules,
            'jenis_kelamin'   => ['nullable', 'in:L,P'],
            'tanggal_lahir'   => ['nullable', 'date', 'before:today'],
            'no_telepon'      => ['required', 'string', 'max:20'],
            'email'           => ['nullable', 'email', 'max:100'],
            'alamat'          => ['nullable', 'string', 'max:255'],
            'kota_asal'       => ['nullable', 'string', 'max:100'],
            'pekerjaan'       => ['nullable', 'string', 'max:100'],
        ];
    }
    public function messages(): array
    {
        return [
            'nama_lengkap.required'    => 'Nama lengkap wajib diisi.',
            'jenis_identitas.required' => 'Jenis identitas wajib dipilih.',
            'nik.required'             => 'Nomor identitas wajib diisi.',
            'nik.digits'               => 'NIK/SIM harus tepat 16 digit angka.',
            'nik.min'                  => 'Nomor paspor minimal 6 karakter.',
            'nik.max'                  => 'Nomor paspor maksimal 20 karakter.',
            'nik.regex'                => 'Nomor paspor hanya boleh huruf dan angka.',
            'nik.unique'               => 'Nomor identitas sudah terdaftar.',
            'no_telepon.required'      => 'Nomor telepon wajib diisi.',
        ];
    }
}