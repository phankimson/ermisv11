<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccVat;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccVatImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $code_check = AccVat::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
            $arr = [
                'id'     => Str::uuid()->toString(),
                'code'    => $row['code'],
                'name'    => $row['name'],
                'name_en'    => $row['name_en'],
                'vat_tax'    => $row['vat_tax'],
                'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
            $data = new AccVatImport();
            $data->setData($arr);
        return new AccVat($arr);
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
