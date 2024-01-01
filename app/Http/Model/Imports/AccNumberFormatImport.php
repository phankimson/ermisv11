<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccNumberFormat;
use App\Http\Model\AccNumberVoucher;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccNumberFormatImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function model(array $row)
    {
        //dump($row);
        $code_check = AccNumberFormat::WhereCheck('code',$row['code'],'id',null)->first();
        $number_voucher = AccNumberVoucher::WhereDefault('code',$row['menu'])->first();
        if($code_check == null){
        return new AccNumberFormat([
           'id'     => Str::uuid()->toString(),
           'number_voucher'    => $number_voucher == null ? 0 : $number_voucher->id,
           'format'    => $row['format'],
           'day'    => $row['day'],
           'month'    => $row['month'],
           'year'    => $row['year'],
           'number'    => $row['number'],
           'length_number'    => $row['length_number'],
           'active'    => $row['active'] == null ? 1 : $row['active'],
            ]);
        }
    }

    public function batchSize(): int
   {
       return 1000;
   }

    public function chunkSize(): int
   {
       return 1000;
   }

}
