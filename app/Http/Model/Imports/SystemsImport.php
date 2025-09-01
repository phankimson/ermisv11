<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Systems;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class SystemsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $code_check = Systems::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
            $arr = [
                'id'     => Str::uuid()->toString(),
                'code'    => $row['code'],
                'name'    => $row['name'],
                'value'    => $row['value'],
                'value1'    => $row['value1'],
                'value2'    => $row['value2'],
                'value3'    => $row['value3'],
                'value4'    => $row['value4'],
                'value5'    => $row['value5'],
                'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
          $data = new SystemsImport();
          $data->setData($arr);
        return new Systems($arr);
         }
    }
    public function batchSize(): int
    {
      return (int) config('excel.setting.IMPORT_SIZE');
    }   
  
     public function limit(): int
     {
      return (int) config('excel.setting.IMPORT_LIMIT');
     }
     
     public function headingRow(): int
     {
         return (int) config('excel.setting.HEADING_ROW');
     }

}
