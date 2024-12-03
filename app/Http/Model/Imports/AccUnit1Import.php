<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccUnit;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccUnit1Import implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
  private static $result = array();

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
        $code_check = AccUnit::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){          
          $arr = [
            'id'     => Str::uuid()->toString(),
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],         
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccUnitImport();
          $data->setData($arr);
          return new AccUnit($arr);
      }
    }

    public function chunkSize(): int
    {
        return env("IMPORT_SIZE",100);
    }

}
