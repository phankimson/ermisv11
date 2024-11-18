<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Country;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class CountryImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $code_check = Country::WhereCheck('code',$row['code'],'id',null)->first();
      if($code_check == null){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'code'    => Convert::StringDefaultformat($row['code']),
          'name'    => Convert::StringDefaultformat($row['name']),
          'phonecode'    => Convert::IntDefaultformat($row['phonecode']),
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new CountryImport();
        $data->setData($arr);
        return new Country($arr);
     }
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
