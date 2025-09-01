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

class ErrorImport implements ToModel, WithHeadingRow, WithBatchInserts , WithLimit
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
        if($row['menu']){
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
