<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccRevenueExpenditure;
use App\Http\Model\AccRevenueExpenditureType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccRevenueExpenditureImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
        //dump($row);
        $type = AccRevenueExpenditureType::WhereDefault('code',$row['type'])->first();
        $code_check = AccRevenueExpenditure::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'type'    => $type == null ? $type : $type->id,
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'description'    => $row['description'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccRevenueExpenditureImport();
          $data->setData($arr);
        return new AccRevenueExpenditure($arr);
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
