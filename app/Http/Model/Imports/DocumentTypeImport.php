<?php

namespace App\Http\Model\Imports;

use App\Http\Model\DocumentType;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DocumentTypeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $code_check = DocumentType::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        return new DocumentType([
           'id'     => Str::uuid()->toString(),
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
