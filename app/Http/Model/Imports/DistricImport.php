<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Distric;
use App\Http\Model\Area;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DistricImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
      $area = Area::WhereDefault('code',$row['area'])->first();
      $code_check = Distric::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'area'    => $area == null ? 0 : $area->id,
          'code'    => Convert::StringDefaultformat($row['code']),
          'name'    => Convert::StringDefaultformat($row['name']),
          'name_en'    => Convert::StringDefaultformat($row['name_en']),
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new DistricImport();
        $data->setData($arr);
        return new Distric($arr);
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
