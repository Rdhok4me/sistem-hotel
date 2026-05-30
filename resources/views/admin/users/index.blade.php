@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')

<div class="flex justify-between items-center mb-5">
    <h1 class="text-xl font-semibold text-gray-800">Kelola Pengguna</h1>
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        + Tambah Pengguna
    </a>
</div>

@include('components.alert')

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50">
                <th class="table-header">Nama</th>
                <th class="table-header">Email</th>
                <th class="table-header">Role</th>
                <th class="table-header">Status</th>
                <th class="table-header">Terdaftar</th>
                <th class="table-header text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="table-cell">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center
                                    text-xs font-semibold text-indigo-700 flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->name }}</span>
                    </div>
                </td>
                <td class="table-cell text-gray-500">{{ $user->email }}</td>
                <td class="table-cell">
                    @if($user->hasRole('admin'))
                        <span class="badge bg-indigo-100 text-indigo-700">Admin</span>
                    @elseif($user->hasRole('resepsionis'))
                        <span class="badge bg-blue-100 text-blue-700">Resepsionis</span>
                    @else
                        <span class="badge bg-gray-100 text-gray-500">-</span>
                    @endif
                </td>
                <td class="table-cell">
                    @if($user->is_active)
                        <span class="badge bg-emerald-100 text-emerald-700">Aktif</span>
                    @else
                        <span class="badge bg-red-100 text-red-700">Nonaktif</span>
                    @endif
                </td>
                <td class="table-cell text-gray-500">
                    {{ $user->created_at->format('d M Y') }}
                </td>
                <td class="table-cell text-right">
                    <div class="flex justify-end items-center gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-xs text-indigo-600 hover:underline">Edit</a>

                        @if($user->id !== auth()->id())
                        <form method="POST"
                              action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                data-confirm="{{ $user->is_active ? 'Nonaktifkan pengguna ini?' : 'Aktifkan pengguna ini?' }}"
                                class="text-xs {{ $user->is_active ? 'text-amber-600' : 'text-emerald-600' }} hover:underline">
                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form method="POST"
                              action="{{ route('admin.users.destroy', $user) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                data-confirm="Hapus pengguna {{ $user->name }}? Tindakan ini tidak bisa dibatalkan."
                                class="text-xs text-red-600 hover:underline">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                    Belum ada pengguna.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
