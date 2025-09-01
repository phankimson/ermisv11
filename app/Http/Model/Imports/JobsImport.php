<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Jobs;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class JobsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
      $code_check = Jobs::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null && $row['code']){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'code'    => Convert::StringDefaultformat($row['code']),
          'name'    => Convert::StringDefaultformat($row['name']),
          'phonecode'    => Convert::IntDefaultformat($row['phonecode']),
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new JobsImport();
        $data->setData($arr);
        return new Jobs($arr);
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
