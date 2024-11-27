<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccAccountTypeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithLimit
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
        $code_check = AccAccountType::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        $arr =[
            'id'     => Str::uuid()->toString(),
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],           
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccAccountTypeImport();
        $data->setData($arr);
        return new AccAccountType($arr);
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
