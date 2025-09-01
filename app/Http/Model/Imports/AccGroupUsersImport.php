<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccGroupUsers;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccGroupUsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
      $com = session('com');
        //dump($row);
        $code_check = AccGroupUsers::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'company_id' => $com->id,
          'name'    => $row['code'],
          'code'    => $row['name'],
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccGroupUsersImport();
        $data->setData($arr);
        return new AccGroupUsers($arr);
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
