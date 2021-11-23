<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PraticalResultsExport implements FromCollection, WithHeadings
{
    protected $myArray;

    public function __construct($myArray)
    {
        $this->myArray = $myArray;
    }

    public function collection()
    {
        return collect($this->myArray);
    }

    public function headings(): array
    {
        return [
            'path_id',
            'student_id',
            'result',
            'message'
        ];
    }
}
