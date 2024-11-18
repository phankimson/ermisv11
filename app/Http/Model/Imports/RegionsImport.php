<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Regions;
use App\Http\Model\Country;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class RegionsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $country = Country::WhereDefault('code',$row['country'])->first();
        $code_check = Regions::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
            $arr = [
                'id'     => Str::uuid()->toString(),
                'country'    => $country == null ? 0 : $country->id,
                'code'    => Convert::StringDefaultformat($row['code']),
                'name'    => Convert::StringDefaultformat($row['name']),
                'name_en'    => Convert::StringDefaultformat($row['name_en']),
                'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
            $data = new RegionsImport();
            $data->setData($arr);
          return new Regions($arr);
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
