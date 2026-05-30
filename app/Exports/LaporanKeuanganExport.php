<?php

namespace App\Exports;

use App\Models\Pembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class LaporanKeuanganExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithTitle,
    ShouldAutoSize,
    WithEvents
{
    protected int $bulan;
    protected int $tahun;
    protected $data;

    public function __construct(int $bulan, int $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;

        $this->data = Pembayaran::with([
            'reservasi.tamu',
            'reservasi.kamar.tipeKamar',
            'reservasi.checkOut',
            'user',
        ])
            ->where('status_bayar', 'lunas')
            ->whereMonth('waktu_bayar', $bulan)
            ->whereYear('waktu_bayar', $tahun)
            ->orderBy('waktu_bayar')
            ->get();
    }

    public function collection()
    {
        return $this->data;
    }

    public function title(): string
    {
        return 'Laporan ' . \Carbon\Carbon::create($this->tahun, $this->bulan)
            ->translatedFormat('F Y');
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Pembayaran',
            'Kode Reservasi',
            'Nama Tamu',
            'NIK',
            'No. Kamar',
            'Tipe Kamar',
            'Tgl. Check-in',
            'Tgl. Check-out',
            'Jumlah Malam',
            'Harga/Malam (Rp)',
            'Biaya Tambahan (Rp)',
            'Total Tagihan (Rp)',
            'Jumlah Bayar (Rp)',
            'Metode Pembayaran',
            'Status Bayar',
            'Waktu Pembayaran',
            'Diproses Oleh',
        ];
    }

    public function map($row): array
    {
        $no = $this->data->search(fn ($item) => $item->id === $row->id) + 1;

        return [
            $no,
            $row->kode_pembayaran,
            $row->reservasi->kode_reservasi,
            $row->reservasi->tamu->nama_lengkap,
            $row->reservasi->tamu->nik,
            $row->reservasi->kamar->nomor_kamar,
            $row->reservasi->kamar->tipeKamar->nama,
            $row->reservasi->tanggal_checkin->format('d/m/Y'),
            $row->reservasi->tanggal_checkout->format('d/m/Y'),
            $row->reservasi->jumlah_malam,
            (float) $row->reservasi->harga_per_malam,
            (float) ($row->reservasi->checkOut?->biaya_tambahan ?? 0),
            (float) $row->reservasi->total_harga + (float) ($row->reservasi->checkOut?->biaya_tambahan ?? 0),
            (float) $row->jumlah_bayar,
            $row->metode_bayar_label,
            strtoupper($row->status_bayar),
            $row->waktu_bayar?->format('d/m/Y H:i') ?? '-',
            $row->user->name,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Style heading (baris 3 setelah insert di AfterSheet)
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet    = $event->sheet->getDelegate();
                $dataRows = $this->data->count();
                $lastData = $dataRows + 3; // +2 judul, +1 heading

                // Insert 2 baris judul di atas
                $sheet->insertNewRowBefore(1, 2);

                // Baris 1 — judul utama
                $sheet->mergeCells('A1:R1');
                $sheet->setCellValue('A1',
                    'LAPORAN KEUANGAN — ' .
                    strtoupper(\Carbon\Carbon::create($this->tahun, $this->bulan)
                        ->translatedFormat('F Y'))
                );
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1e1b4b']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(34);

                // Baris 2 — sub-judul
                $sheet->mergeCells('A2:R2');
                $sheet->setCellValue('A2',
                    'Dicetak: ' . now()->translatedFormat('d F Y, H:i') .
                    '   |   Total transaksi: ' . $this->data->count() . ' pembayaran' .
                    '   |   Total pemasukan: Rp ' . number_format($this->data->sum('jumlah_bayar'), 0, ',', '.')
                );
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['size' => 10, 'color' => ['rgb' => '6b7280']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(20);

                // Baris 3 — heading kolom
                $sheet->getStyle('A3:R3')->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4f46e5']],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders' => ['allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['rgb' => 'FFFFFF'],
                    ]],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(28);

                // Baris data — zebra stripes & border
                for ($i = 4; $i <= $lastData; $i++) {
                    $bg = $i % 2 === 0 ? 'F8F9FF' : 'FFFFFF';
                    $sheet->getStyle("A{$i}:R{$i}")->applyFromArray([
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                        'borders'   => ['allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => 'E5E7EB'],
                        ]],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
                    ]);
                }

                // Format kolom angka rupiah (K=11, L=12, M=13, N=14)
                foreach (['K', 'L', 'M', 'N'] as $col) {
                    $sheet->getStyle("{$col}4:{$col}{$lastData}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                // Baris total
                $totalRow = $lastData + 1;
                $sheet->mergeCells("A{$totalRow}:J{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL PEMASUKAN');
                $sheet->setCellValue("M{$totalRow}", "=SUM(M4:M{$lastData})");
                $sheet->setCellValue("N{$totalRow}", "=SUM(N4:N{$lastData})");

                foreach (['M', 'N'] as $col) {
                    $sheet->getStyle("{$col}{$totalRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                $sheet->getStyle("A{$totalRow}:R{$totalRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '1e1b4b']],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'EEF2FF']],
                    'borders'   => ['top' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '4f46e5']]],
                ]);

                // Freeze header
                $sheet->freezePane('A4');
            },
        ];
    }
}
