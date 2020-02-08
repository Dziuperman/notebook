<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Spatie\Activitylog\Models\Activity;

class ActivityLogExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Activity::where('causer_id', '=', \Auth::user()->id);
    }

    public function map($activity): array
    {
        return [
            $activity->description,
            $activity->created_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Description',
            'Created at',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet
                    ->getStyle('A1:B1')
                    ->applyFromArray(['font' => ['bold' => true,]]);
            }
        ];
    }
}
