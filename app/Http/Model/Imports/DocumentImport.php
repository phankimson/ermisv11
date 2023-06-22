<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Document;
use App\Http\Model\DocumentType;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DocumentImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $type = DocumentType::WhereDefault('code',$row['type'])->first();
      $code_check = Document::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        return new Document([
           'id'     => Str::uuid()->toString(),
           'type'    => $type == null ? 0 : $type->id,
           'code'    => Convert::StringDefaultformat($row['code']),
           'name'    => Convert::StringDefaultformat($row['name']),
           'name_en'    => Convert::StringDefaultformat($row['name_en']),
           'date_start'    =>  Convert::dateDefaultformat($row['date_start'],'Y-m-d'),
           'date_end'    => Convert::dateDefaultformat($row['date_end'],'Y-m-d'),
           'description'    => Convert::StringDefaultformat($row['description']),
           'content'    => Convert::StringDefaultformat($row['content']),
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
