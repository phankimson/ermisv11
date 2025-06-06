<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Document;
use App\Http\Model\DocumentType;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DocumentImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{
  private static $result = array();
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }
    public function setData($arr)
    {
        array_push(self::$result,$arr);
    } 

    public function getData()
    {
        return self::$result;
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
        $arr = [
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
        ];
        $data = new DocumentImport();
        $data->setData($arr);
        return new Document($arr);
     }
    }

    public function batchSize(): int
    {
      return env("IMPORT_SIZE",100);
    }   
  
     public function limit(): int
     {
      return env("IMPORT_LIMIT",200);
     }
     public function headingRow(): int
     {
         return env("HEADING_ROW",1);
     }
       public function startRow(): int
     {
         return env("START_ROW",2);
     }
}
