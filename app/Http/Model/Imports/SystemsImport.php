<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Systems;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SystemsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $code_check = Systems::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new Systems([
           'id'     => Str::uuid()->toString(),
           'code'    => $row['code'],
           'name'    => $row['name'],
           'value'    => $row['value'],
           'value1'    => $row['value1'],
           'value2'    => $row['value2'],
           'value3'    => $row['value3'],
           'value4'    => $row['value4'],
           'value5'    => $row['value5'],
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
