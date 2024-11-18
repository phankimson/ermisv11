<?php

namespace App\Http\Model\Imports;

use App\Http\Model\HistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class HistoryActionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
       return 1000;
   }

    public function chunkSize(): int
   {
       return 1000;
   }
}
