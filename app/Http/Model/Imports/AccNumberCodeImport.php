<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccNumberCode;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccNumberCodeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading , WithLimit
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
          $code_check = AccNumberCode::WhereCheck('code',$row['code'],'id',null)->first();
          $menu = Menu::WhereDefault('code',$row['menu'])->first();
          if($code_check == null){
            $arr = [
              'id'     => Str::uuid()->toString(),
              'menu_id'    => $menu == null ? 0 : $menu->id,
              'code'    => $row['code'],
              'name'    => $row['name'],
              'suffixes'    => $row['suffixes'],
              'prefix'    => $row['prefix'],
              'number'    => $row['number'],
              'length_number'    => $row['length_number'],
              'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
            $data = new AccNumberCodeImport();
            $data->setData($arr);
          return new AccNumberCode($arr);
        }
    }

    public function batchSize(): int
    {
        return 200;
    }
 
     public function chunkSize(): int
    {
        return 200;
    }
 
    public function limit(): int
    {
        return 1000;
    }

}
