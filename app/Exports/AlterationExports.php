<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AlterationExports implements FromCollection, WithHeadings{
    public function headings():array{
        return [];
    }
    public function collection(){}
}