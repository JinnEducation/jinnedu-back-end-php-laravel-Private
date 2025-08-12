<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Events\AfterSheet;

class RevenueReportExport implements FromArray, WithStyles, WithEvents, WithTitle
{
    protected $revenues;

    public function __construct($revenues)
    {
        $this->revenues = $revenues;
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['Period', 'Orders Count', 'Total Amount', 'Total Expenses', 'Net Revenue'];

        foreach ($this->revenues as $revenue) {
            $data[] = [
                $revenue->period,
                $revenue->orders_count,
                number_format($revenue->total_amount, 2),
                number_format($revenue->total_expenses ?? 0, 2),
                number_format($revenue->net_revenue ?? 0, 2),
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Auto size columns
                foreach (range('A', 'E') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Revenue';
    }
}
