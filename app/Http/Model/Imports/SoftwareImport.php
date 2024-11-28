<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Software;
use Illuminate\Support\Str;
use Hashids\Hashids;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class SoftwareImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $code_check = Software::WhereCheck('url',$row['url'],'id',null)->first();
        $hashids = new Hashids('',50);
        if($code_check == null){
          $arr =[
            'id'     => Str::uuid()->toString(),
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'image'    => $row['image'],
            'url'    => $row['url'],
            'database_temp'    => $row['database_temp'],
            'username_temp'    => $row['username_temp'],
            'password_temp'    => $hashids->encode($row['password_temp']),
            'note'    => $row['note'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new SoftwareImport();
          $data->setData($arr);
        return new Software($arr);
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

}
