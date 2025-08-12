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

class ConferenceExport implements FromArray, WithStyles, WithEvents, WithTitle
{
    protected $conferences;

    public function __construct($conferences)
    {
        $this->conferences = $conferences;
    }

    public function array(): array
    {
        $data = [];

        $data[] = ['Conference', 'Student Name', 'Tutor Name', 'Start Date', 'End Date'];

        foreach ($this->conferences as $conference) {
            $data[] = [
                $conference->title,
                $conference->student->name ?? '-',
                $conference->tutor->name ?? '-',
                $conference->start_date_time,
                $conference->end_date_time,
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
        return 'Conferences';
    }
}
