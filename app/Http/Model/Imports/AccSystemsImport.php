<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccSystems;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccSystemsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $code_check = AccSystems::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new AccSystems([
           'id'     => Str::uuid()->toString(),
           'code'    => $row['code'],
           'name'    => $row['name'],
           'value'    => $row['value'],
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
