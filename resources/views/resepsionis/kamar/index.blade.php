@extends('layouts.app')

@section('title', 'Data Kamar')

@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="text-xl font-semibold text-gray-800">Data Kamar Hotel</h1>
    @can('kamar.create')
    <a href="{{ route('kamar.create') }}" class="btn-primary">+ Tambah Kamar</a>
    @endcan
</div>

@include('components.alert')

{{-- Stat status --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    @php
        $statConfig = [
            'tersedia'    => ['label' => 'Tersedia',    'class' => 'bg-emerald-50 text-emerald-700', 'dot' => 'bg-emerald-400'],
            'terisi'      => ['label' => 'Terisi',      'class' => 'bg-blue-50 text-blue-700',       'dot' => 'bg-blue-400'],
            'dibersihkan' => ['label' => 'Dibersihkan', 'class' => 'bg-amber-50 text-amber-700',     'dot' => 'bg-amber-400'],
            'maintenance' => ['label' => 'Maintenance', 'class' => 'bg-red-50 text-red-700',         'dot' => 'bg-red-400'],
        ];
    @endphp
    @foreach($statConfig as $key => $cfg)
    <a href="{{ route('kamar.index', ['status' => $key]) }}"
       class="rounded-xl p-4 {{ $cfg['class'] }} hover:opacity-90 transition-opacity">
        <div class="flex items-center gap-2 mb-1">
            <span class="w-2 h-2 rounded-full {{ $cfg['dot'] }}"></span>
            <span class="text-xs font-medium">{{ $cfg['label'] }}</span>
        </div>
        <p class="text-2xl font-semibold">{{ $stats[$key] }}</p>
        <p class="text-xs opacity-60 mt-0.5">kamar</p>
    </a>
    @endforeach
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('kamar.index') }}" class="flex gap-2 mb-5">
    <select name="tipe" onchange="this.form.submit()" class="form-input w-auto">
        <option value="">Semua tipe</option>
        @foreach($tipeKamar as $t)
            <option value="{{ $t->id }}" {{ request('tipe') == $t->id ? 'selected' : '' }}>
                {{ $t->nama }}
            </option>
        @endforeach
    </select>
    <select name="status" onchange="this.form.submit()" class="form-input w-auto">
        <option value="">Semua status</option>
        @foreach(['tersedia','terisi','dibersihkan','maintenance'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucfirst($s) }}
            </option>
        @endforeach
    </select>
    @if(request()->hasAny(['tipe','status']))
        <a href="{{ route('kamar.index') }}" class="btn-secondary">Reset</a>
    @endif
</form>

{{-- Tabel kamar --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50">
                <th class="table-header">No. Kamar</th>
                <th class="table-header">Lantai</th>
                <th class="table-header">Tipe</th>
                <th class="table-header">Harga/Malam</th>
                <th class="table-header">Status</th>
                <th class="table-header">Keterangan</th>
                <th class="table-header text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($kamar as $k)
            @php $badge = $k->status_badge; @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="table-cell font-semibold text-gray-800">
                    {{ $k->nomor_kamar }}
                </td>
                <td class="table-cell text-gray-500">Lantai {{ $k->lantai }}</td>
                <td class="table-cell">{{ $k->tipeKamar->nama }}</td>
                <td class="table-cell text-gray-700">
                    Rp {{ number_format($k->tipeKamar->harga_per_malam, 0, ',', '.') }}
                </td>
                <td class="table-cell">
                    <span class="badge {{ $badge['class'] }}">{{ $badge['label'] }}</span>
                </td>
                <td class="table-cell text-gray-400 text-xs">
                    {{ $k->keterangan ?? '-' }}
                </td>
                <td class="table-cell text-right">
                    <div class="flex justify-end gap-2 items-center">

                        @can('kamar.edit')
                        {{-- Dropdown ubah status --}}
                        <form method="POST" action="{{ route('kamar.status', $k) }}" class="inline">
                            @csrf @method('PATCH')
                            <select name="status" onchange="this.form.submit()"
                                class="text-xs border border-gray-200 rounded px-2 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-300 cursor-pointer">
                                @foreach(['tersedia','dibersihkan','maintenance','terisi'] as $s)
                                    <option value="{{ $s }}" {{ $k->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <a href="{{ route('kamar.edit', $k) }}"
                           class="text-xs text-indigo-600 hover:underline">Edit</a>
                        @endcan

                        @can('kamar.delete')
                        <form method="POST" action="{{ route('kamar.destroy', $k) }}" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit"
                                data-confirm="Hapus kamar {{ $k->nomor_kamar }}?"
                                class="text-xs text-red-600 hover:underline">
                                Hapus
                            </button>
                        </form>
                        @endcan

                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-400">
                    Tidak ada kamar yang sesuai filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($kamar->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $kamar->links() }}
    </div>
    @endif
</div>

@endsection