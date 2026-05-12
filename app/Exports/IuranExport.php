<?php

namespace App\Exports;

use App\Models\IuranTransactionDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class IuranExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle
{
    protected ?int $tahun;
    protected ?string $status;

    /**
     * @param int|null    $tahun   Filter berdasarkan tahun_iuran (opsional)
     * @param string|null $status  Filter berdasarkan status transaksi: progress|verified|success (opsional)
     */
    public function __construct(?int $tahun = null, ?string $status = null)
    {
        $this->tahun  = $tahun;
        $this->status = $status;
    }

    // =========================================================================
    // DATA QUERY
    // =========================================================================
    public function query()
    {
        return IuranTransactionDetail::query()
            ->with(['user.dialisis', 'iuran'])
            ->when($this->tahun,  fn($q) => $q->where('tahun_iuran', $this->tahun))
            ->when($this->status, fn($q) => $q->whereHas('iuran', fn($q2) => $q2->where('status', $this->status)))
            ->orderBy('tahun_iuran', 'desc')
            ->orderBy('id', 'desc');
    }

    // =========================================================================
    // NAMA SHEET
    // =========================================================================
    public function title(): string
    {
        $label = 'Data Iuran';
        if ($this->tahun)  $label .= ' ' . $this->tahun;
        if ($this->status) $label .= ' - ' . ucfirst($this->status);
        return $label;
    }

    // =========================================================================
    // HEADER KOLOM
    // =========================================================================
    public function headings(): array
    {
        return [
            'No',
            'Nama Anggota',
            'NIRA IPDI',
            'Center Dialisis',
            'Tahun Bayar',
            'Tahun Iuran',
            'Bukti Bayar',
        ];
    }

    // =========================================================================
    // MAPPING DATA PER ROW
    // =========================================================================
    public function map($detail): array
    {
        static $no = 0;
        $no++;

        $tahunBayar = $detail->iuran?->date
            ? \Carbon\Carbon::parse($detail->iuran->date)->year
            : '-';

        $buktiBayar = $detail->iuran?->iuran_image_transfer
            ? url($detail->iuran->iuran_image_transfer)
            : '-';

        return [
            $no,
            $detail->user?->username          ?? '-',
            $detail->user?->nira_ipdi         ?? '-',
            $detail->user?->dialisis?->name   ?? '-',
            $tahunBayar,
            $detail->tahun_iuran              ?? '-',
            $buktiBayar,
        ];
    }

    // =========================================================================
    // LEBAR KOLOM (manual — lebih presisi dari ShouldAutoSize)
    // =========================================================================
    public function columnWidths(): array
    {
        return [
            'A' => 6,   // No
            'B' => 30,  // Nama Anggota
            'C' => 20,  // NIRA IPDI
            'D' => 28,  // Center Dialisis
            'E' => 14,  // Tahun Bayar
            'F' => 14,  // Tahun Iuran
            'G' => 60,  // Bukti Bayar (URL panjang)
        ];
    }

    // =========================================================================
    // STYLING
    // =========================================================================
    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        // Header row — background biru gelap, teks putih bold, center
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold'  => true,
                'color' => ['argb' => 'FFFFFFFF'],
                'size'  => 11,
                'name'  => 'Arial',
            ],
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF1E3A5F'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(22);

        // Stripe baris data — putih / abu-abu muda bergantian
        for ($row = 2; $row <= $lastRow; $row++) {
            $bgColor = ($row % 2 === 0) ? 'FFEAF1FB' : 'FFFFFFFF';
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'font' => [
                    'name' => 'Arial',
                    'size' => 10,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => $bgColor],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ]);
        }

        // Kolom No — center
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kolom Tahun Bayar & Tahun Iuran — center
        $sheet->getStyle("E2:F{$lastRow}")->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Kolom Bukti Bayar — wrap text agar URL tidak terpotong
        $sheet->getStyle("G2:G{$lastRow}")->getAlignment()
            ->setWrapText(true);

        // Border tipis seluruh tabel
        $sheet->getStyle("A1:G{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FFD0D7E3'],
                ],
            ],
        ]);

        // Freeze baris header
        $sheet->freezePane('A2');

        return [];
    }
}