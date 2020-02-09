<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use App\Customer;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromQuery, WithMapping, WithHeadings, WithColumnFormatting, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function query()
    {
        return Customer::where('user_id', '=', \Auth::user()->id);
    }

    public function map($customer): array
    {
        return [
            $customer->name,
            $customer->email,
            $customer->phone,
            $customer->website,
            $customer->created_at,
            $customer->updated_at,
        ];
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Phone',
            'Website',
            'Created at',
            'Updated at'
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'C' => NumberFormat::FORMAT_NUMBER
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet
                    ->getStyle('A1:F1')
                    ->applyFromArray(['font' => ['bold' => true,]]);
            }
        ];
    }
}
