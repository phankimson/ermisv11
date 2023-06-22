<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Country;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class CountryImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $code_check = Country::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        return new Country([
           'id'     => Str::uuid()->toString(),
           'code'    => Convert::StringDefaultformat($row['code']),
           'name'    => Convert::StringDefaultformat($row['name']),
           'phonecode'    => Convert::IntDefaultformat($row['phonecode']),
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
