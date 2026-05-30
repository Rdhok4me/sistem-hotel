@extends('layouts.app')
@section('title', 'Data Tamu')
@section('content')

<div class="flex justify-between items-center mb-5">
    <p class="text-sm text-gray-400">{{ $tamu->total() }} tamu terdaftar</p>
    <a href="{{ route('tamu.create') }}"
       class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-lg transition">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tamu Baru
    </a>
</div>

{{-- Pencarian --}}
<div class="bg-white rounded-xl border border-gray-100 p-4 mb-4">
    <form method="GET" action="{{ route('tamu.index') }}" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama, NIK, atau nomor telepon..."
            class="flex-1 text-sm border border-gray-200 rounded-lg px-3 py-2
                   focus:outline-none focus:ring-2 focus:ring-indigo-300">
        <button type="submit"
            class="text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition">
            Cari
        </button>
        @if(request('search'))
        <a href="{{ route('tamu.index') }}" class="text-sm text-gray-500 px-3 py-2">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100 bg-gray-50">
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Tamu</th>
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">NIK/No. Identitas</th>
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Telepon</th>
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Kota</th>
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Riwayat</th>
                <th class="text-left text-xs text-gray-500 font-medium px-4 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tamu as $t)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                            <span class="text-indigo-700 font-semibold text-xs">{{ $t->inisial }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $t->nama_lengkap }}</p>
                            <p class="text-xs text-gray-400">{{ $t->jenis_kelamin_lengkap }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $t->nik }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $t->no_telepon }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $t->kota_asal ?? '-' }}</td>
                <td class="px-4 py-3">
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                        {{ $t->reservasi_count }} reservasi
                    </span>
                </td>
                <td class="px-4 py-3 flex items-center gap-3">
                    <a href="{{ route('tamu.show', $t) }}" class="text-indigo-600 hover:underline text-xs">Detail</a>
                    <a href="{{ route('tamu.edit', $t) }}" class="text-gray-500 hover:underline text-xs">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400 text-sm">
                    Tidak ada data tamu.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tamu->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">{{ $tamu->links() }}</div>
    @endif
</div>
@endsection
