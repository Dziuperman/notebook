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
            $this->getProperty($activity, 'name'),
            $this->getProperty($activity, 'email'),
            $this->getProperty($activity, 'phone'),
            $this->getProperty($activity, 'website'),
            $activity->properties['attributes']['created_at'],
            $activity->properties['attributes']['updated_at'],
        ];
    }

    public function getProperty($array, $prop)
    {

        if(isset($array->properties['old']) && $array->properties['old'][$prop] !== $array->properties['attributes'][$prop]) {
            return 'New: ' . $array->properties['attributes'][$prop] . '; Old: ' . $array->properties['old'][$prop];
        }

        return $array->properties['attributes'][$prop];
    }

    public function headings(): array
    {
        return [
            'Description',
            'Date',
            'Name',
            'Email',
            'Phone',
            'Website',
            'Created at',
            'Updated at',
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'E' => "##,#0",
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet
                    ->getStyle('A1:H1')
                    ->applyFromArray(['font' => ['bold' => true,]]);
            }
        ];
    }
}
