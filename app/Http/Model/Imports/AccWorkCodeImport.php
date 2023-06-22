<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccWorkCode;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccWorkCodeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $code_check = AccWorkCode::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new AccWorkCode([
            'id'     => Str::uuid()->toString(),
           'code'    => $row['code'],
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'description'    => $row['description'],
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
