<?php

namespace App\Http\Model\Imports;

use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class HistoryActionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
      $user = User::WhereDefault('username',$row['user'])->first();
      $menu = Menu::WhereDefault('code',$row['menu'])->first();
        //dump($row);
        $arr = [
          'id'     => Str::uuid()->toString(),
          'url'    => $row['url'],
          'type'    => $row['type'],
          'user'    =>  $user == null ? 0 : $user->id,
          'menu'    =>  $menu == null ? 0 : $menu->id,
          'dataz'    => $row['dataz'],
        ];
        $data = new HistoryActionImport();
        $data->setData($arr);
        return new HistoryAction($arr);
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
