<?php
namespace App\Exports;

use App\Models\AlterationExport;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlterationExports implements FromCollection, WithHeadings, ShouldAutoSize{

    public function headings():array{
        return [
            'TRADING NAME',
            'LICENCE NUMBER',
            'PROVINCE/REGION',
            'DATE LODGED',
            'PROOF OF LODGIMENT',
            'DATE GRANTED',
            'CURRENT STATUS',
            'COMMENT IF APPLICABLE'
        ];
    }
    public function collection(){
        return AlterationExport::get([
        'trading_name',
        'licence_number',
        'province',
        'date_logded',
        'date_granted',
        'notes'
        ]);
    }
}