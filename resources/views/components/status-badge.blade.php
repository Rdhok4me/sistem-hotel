@props(['status', 'type' => 'reservasi'])

@php
    if ($type === 'reservasi') {
        $map = [
            'pending'    => ['class' => 'bg-yellow-100 text-yellow-800', 'label' => 'Pending'],
            'konfirmasi' => ['class' => 'bg-blue-100 text-blue-800',     'label' => 'Konfirmasi'],
            'checkin'    => ['class' => 'bg-emerald-100 text-emerald-800','label' => 'Check-in'],
            'checkout'   => ['class' => 'bg-gray-100 text-gray-600',     'label' => 'Check-out'],
            'batal'      => ['class' => 'bg-red-100 text-red-700',       'label' => 'Batal'],
        ];
    } else {
        $map = [
            'tersedia'    => ['class' => 'bg-emerald-100 text-emerald-800', 'label' => 'Tersedia'],
            'terisi'      => ['class' => 'bg-blue-100 text-blue-800',       'label' => 'Terisi'],
            'dibersihkan' => ['class' => 'bg-amber-100 text-amber-800',     'label' => 'Dibersihkan'],
            'maintenance' => ['class' => 'bg-red-100 text-red-700',         'label' => 'Maintenance'],
        ];
    }
    $badge = $map[$status] ?? ['class' => 'bg-gray-100 text-gray-600', 'label' => ucfirst($status)];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badge['class'] }}">
    {{ $badge['label'] }}
</span>
