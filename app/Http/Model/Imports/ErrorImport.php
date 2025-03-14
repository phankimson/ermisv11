<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Error;
use App\Http\Model\Menu;
use App\Http\Model\User;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ErrorImport implements ToModel, WithHeadingRow, WithBatchInserts , WithLimit, WithStartRow
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
        $user = User::WhereDefault('username',$row['username'])->first();
        $menu = Menu::WhereDefault('code',$row['menu'])->first();
        $arr = [
            'id'     => Str::uuid()->toString(),
            'type'    => Convert::IntDefaultformat($row['type']),
            'url'    => Convert::StringDefaultformat($row['url']),
            'user_id'    => $user == null ? 0 : $user->id,
            'menu_id'    => $menu == null ? 0 : $menu->id,
            'error'    => Convert::StringDefaultformat($row['error']),
            'check'    => $row['check'] == null ? 1 : $row['check'],
        ];
        $data = new ErrorImport();
        $data->setData($arr);
        return new Error($arr);
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
