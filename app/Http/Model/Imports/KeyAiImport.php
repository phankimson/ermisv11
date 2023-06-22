<?php

namespace App\Http\Model\Imports;

use App\Http\Model\KeyAi;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class KeyAiImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $code_check = KeyAi::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        return new KeyAi([
           'id'     => Str::uuid()->toString(),
           'content'    => Convert::StringDefaultformat($row['content']),
           'count'    => Convert::IntDefaultformat($row['count']),
           'code'    => Convert::StringDefaultformat($row['code']),
           'name'    => Convert::StringDefaultformat($row['name']),
           'name_en'    => Convert::StringDefaultformat($row['name_en']),
           'field'    => Convert::StringDefaultformat($row['field']),
           'crit'    => Convert::StringDefaultformat($row['crit']),
           'crit_en'    => Convert::StringDefaultformat($row['crit_en'] == null),
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
