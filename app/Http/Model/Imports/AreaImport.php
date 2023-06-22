<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Area;
use Illuminate\Support\Str;
use App\Http\Model\Regions;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AreaImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $regions = Regions::WhereDefault('code',$row['regions'])->first();
      $code_check = Area::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        //dump($row);
        return new Area([
           'id'     => Str::uuid()->toString(),
           'regions'    =>  $regions == null ? 0 : $regions->id,
           'code'    => Convert::StringDefaultformat($row['code']),
           'name'    => Convert::StringDefaultformat($row['name']),
           'name_en'    => Convert::StringDefaultformat($row['name_en']),
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
