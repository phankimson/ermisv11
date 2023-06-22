<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Software;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Hashids\Hashids;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SoftwareImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $code_check = Software::WhereCheck('url',$row['url'],'id',null)->first();
        $hashids = new Hashids('',50);
        if($code_check == null){
        return new Software([
           'id'     => Str::uuid()->toString(),
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'image'    => $row['image'],
           'url'    => $row['url'],
           'database_temp'    => $row['database_temp'],
           'username_temp'    => $row['username_temp'],
           'password_temp'    => $hashids->encode($row['password_temp']),
           'note'    => $row['note'],
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
