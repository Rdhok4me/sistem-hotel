<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'DejaVu Sans', Arial, sans-serif;
    font-size: 12px;
    color: #1a1a2e;
    line-height: 1.5;
    background: #fff;
}

.page { padding: 40px 48px; }

/* ── Header ──────────────────────────────── */
.header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding-bottom: 20px;
    border-bottom: 2px solid #4f46e5;
    margin-bottom: 28px;
}
.hotel-name {
    font-size: 20px;
    font-weight: bold;
    color: #4f46e5;
    margin-bottom: 4px;
}
.hotel-sub { font-size: 10px; color: #6b7280; line-height: 1.6; }

.invoice-box { text-align: right; }
.invoice-label { font-size: 22px; font-weight: bold; color: #111; }
.invoice-kode  { font-size: 11px; color: #6b7280; margin-top: 3px; }

/* ── Info grid ───────────────────────────── */
.info-grid {
    display: flex;
    gap: 20px;
    margin-bottom: 28px;
}
.info-box { flex: 1; }
.info-box .title {
    font-size: 9px;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #9ca3af;
    font-weight: bold;
    margin-bottom: 6px;
    border-bottom: 1px solid #f3f4f6;
    padding-bottom: 4px;
}
.info-box .main-name {
    font-size: 13px;
    font-weight: bold;
    color: #111;
    margin-bottom: 3px;
}
.info-box .detail { font-size: 11px; color: #374151; line-height: 1.7; }

/* ── Tabel ───────────────────────────────── */
.tabel {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
}
.tabel thead tr {
    background: #4f46e5;
    color: #fff;
}
.tabel thead th {
    padding: 9px 12px;
    text-align: left;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.tabel tbody tr:nth-child(even) { background: #f9fafb; }
.tabel tbody td { padding: 10px 12px; font-size: 11px; color: #374151; }
.tabel tbody tr { border-bottom: 1px solid #f3f4f6; }
.align-right { text-align: right; }

/* ── Total ───────────────────────────────── */
.total-wrap { margin-top: 8px; }
.total-row {
    display: flex;
    justify-content: flex-end;
    gap: 0;
    margin-bottom: 0;
}
.total-table { width: 280px; margin-left: auto; border-collapse: collapse; }
.total-table td { padding: 5px 10px; font-size: 11px; }
.total-table .label { color: #6b7280; }
.total-table .amount { text-align: right; color: #111; }
.total-table .grand-row td {
    border-top: 2px solid #4f46e5;
    padding-top: 9px;
    font-weight: bold;
    font-size: 13px;
}
.total-table .grand-row .amount { color: #4f46e5; }

/* ── Badge ───────────────────────────────── */
.badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: bold;
}
.badge-lunas { background: #d1fae5; color: #065f46; }
.badge-dp    { background: #fef3c7; color: #92400e; }
.badge-belum { background: #fee2e2; color: #991b1b; }

/* ── Tanda tangan ────────────────────────── */
.ttd-section {
    margin-top: 36px;
    display: flex;
    justify-content: flex-end;
}
.ttd-box { text-align: center; }
.ttd-box .kota-tgl { font-size: 10px; color: #6b7280; margin-bottom: 40px; }
.ttd-box .garis { border-bottom: 1px solid #9ca3af; width: 160px; margin: 0 auto 6px; }
.ttd-box .nama { font-size: 11px; font-weight: bold; }
.ttd-box .jabatan { font-size: 10px; color: #9ca3af; }

/* ── Footer ──────────────────────────────── */
.footer {
    margin-top: 32px;
    padding-top: 14px;
    border-top: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    font-size: 9px;
    color: #9ca3af;
}
</style>
</head>
<body>
<div class="page">

    {{-- Header hotel --}}
    <div class="header">
        <div>
            <div class="hotel-name">{{ config('app.name') }}</div>
            <div class="hotel-sub">
                Jl. Contoh No. 123, Kota, Indonesia<br>
                Telp: (021) 000-0000 &nbsp;|&nbsp; Email: info@hotel.com<br>
                www.hotel.com
            </div>
        </div>
        <div class="invoice-box">
            <div class="invoice-label">INVOICE</div>
            <div class="invoice-kode">{{ $reservasi->kode_reservasi }}</div>
            <div class="invoice-kode">Tanggal: {{ now()->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Info tamu, menginap, pembayaran --}}
    <div class="info-grid">
        <div class="info-box">
            <div class="title">Ditagihkan kepada</div>
            <div class="main-name">{{ $reservasi->tamu->nama_lengkap }}</div>
            <div class="detail">
                NIK: {{ $reservasi->tamu->nik }}<br>
                Telp: {{ $reservasi->tamu->no_telepon }}<br>
                {{ $reservasi->tamu->alamat ?? '-' }}
            </div>
        </div>

        <div class="info-box">
            <div class="title">Detail menginap</div>
            <div class="detail">
                <strong>Kamar:</strong> {{ $reservasi->kamar->nomor_kamar }}
                ({{ $reservasi->kamar->tipeKamar->nama }})<br>
                <strong>Check-in:</strong>
                    {{ $reservasi->tanggal_checkin->format('d M Y') }}
                    @if($reservasi->checkIn)
                        ({{ $reservasi->checkIn->waktu_checkin->format('H:i') }})
                    @endif<br>
                <strong>Check-out:</strong>
                    {{ $reservasi->tanggal_checkout->format('d M Y') }}
                    @if($reservasi->checkOut)
                        ({{ $reservasi->checkOut->waktu_checkout->format('H:i') }})
                    @endif<br>
                <strong>Durasi:</strong> {{ $reservasi->jumlah_malam }} malam<br>
                <strong>Jumlah tamu:</strong> {{ $reservasi->jumlah_tamu }} orang
            </div>
        </div>

        <div class="info-box">
            <div class="title">Status pembayaran</div>
            @if($reservasi->pembayaran)
                @php
                    $sb = $reservasi->pembayaran->status_bayar;
                    $badgeCls = match($sb) {
                        'lunas'       => 'badge-lunas',
                        'dp'          => 'badge-dp',
                        default       => 'badge-belum',
                    };
                @endphp
                <div class="detail">
                    <span class="badge {{ $badgeCls }}">{{ strtoupper($sb) }}</span><br><br>
                    <strong>Metode:</strong>
                        {{ $reservasi->pembayaran->metode_bayar_label }}<br>
                    <strong>Waktu bayar:</strong>
                        {{ $reservasi->pembayaran->waktu_bayar?->format('d M Y H:i') ?? '-' }}<br>
                    <strong>Diproses oleh:</strong>
                        {{ $reservasi->pembayaran->user->name ?? '-' }}
                </div>
            @else
                <span class="badge badge-belum">BELUM BAYAR</span>
            @endif
        </div>
    </div>

    {{-- Tabel rincian biaya --}}
    <table class="tabel">
        <thead>
            <tr>
                <th style="width:40%">Keterangan</th>
                <th>Harga / malam</th>
                <th>Durasi</th>
                <th class="align-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            {{-- Biaya kamar --}}
            <tr>
                <td>
                    Kamar {{ $reservasi->kamar->nomor_kamar }}
                    — {{ $reservasi->kamar->tipeKamar->nama }}<br>
                    <span style="font-size:10px; color:#9ca3af;">
                        {{ $reservasi->tanggal_checkin->format('d M') }}
                        s/d {{ $reservasi->tanggal_checkout->format('d M Y') }}
                    </span>
                </td>
                <td>Rp {{ number_format($reservasi->harga_per_malam, 0, ',', '.') }}</td>
                <td>{{ $reservasi->jumlah_malam }} malam</td>
                <td class="align-right">
                    Rp {{ number_format((float)$reservasi->harga_per_malam * $reservasi->jumlah_malam, 0, ',', '.') }}
                </td>
            </tr>

            {{-- Biaya tambahan (jika ada) --}}
            @if($reservasi->checkOut && (float)$reservasi->checkOut->biaya_tambahan > 0)
            <tr>
                <td>
                    Biaya tambahan<br>
                    <span style="font-size:10px; color:#9ca3af;">
                        {{ $reservasi->checkOut->keterangan_biaya ?? 'Biaya tambahan lain-lain' }}
                    </span>
                </td>
                <td>—</td>
                <td>—</td>
                <td class="align-right">
                    Rp {{ number_format($reservasi->checkOut->biaya_tambahan, 0, ',', '.') }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-wrap">
        @php
            $subtotal      = (float) $reservasi->harga_per_malam * $reservasi->jumlah_malam;
            $biayaTambahan = (float) ($reservasi->checkOut?->biaya_tambahan ?? 0);
            $grandTotal    = $subtotal + $biayaTambahan;
            $sudahBayar    = (float) ($reservasi->pembayaran?->jumlah_bayar ?? 0);
            $sisaBayar     = max(0, $grandTotal - $sudahBayar);
        @endphp
        <table class="total-table">
            <tr>
                <td class="label">Subtotal kamar</td>
                <td class="amount">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
            </tr>
            @if($biayaTambahan > 0)
            <tr>
                <td class="label">Biaya tambahan</td>
                <td class="amount">Rp {{ number_format($biayaTambahan, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($sudahBayar > 0 && $sisaBayar > 0)
            <tr>
                <td class="label">Sudah dibayar</td>
                <td class="amount" style="color:#059669;">
                    - Rp {{ number_format($sudahBayar, 0, ',', '.') }}
                </td>
            </tr>
            @endif
            <tr class="grand-row">
                <td class="label">{{ $sisaBayar > 0 ? 'Sisa tagihan' : 'Total' }}</td>
                <td class="amount">
                    Rp {{ number_format($sisaBayar > 0 ? $sisaBayar : $grandTotal, 0, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    {{-- Tanda tangan --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="kota-tgl">
                {{ now()->translatedFormat('d F Y') }}
            </div>
            <div class="garis"></div>
            <div class="nama">{{ $reservasi->user->name }}</div>
            <div class="jabatan">Resepsionis</div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>Dokumen ini digenerate otomatis oleh sistem. Simpan sebagai bukti pembayaran resmi.</div>
        <div>{{ config('app.name') }} &copy; {{ date('Y') }}</div>
    </div>

</div>
</body>
</html>
