<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccExcise;
use App\Http\Model\AccUnit;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccExciseImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithLimit
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
        $unit = AccUnit::WhereDefault('code',$row['unit'])->first();
        $parent = AccExcise::WhereDefault('code',$row['parent'])->first();
        $code_check = AccExcise::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'parent_id'    => $parent == null ? $parent : $parent->id,
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'unit_id'    => $unit == null ? 0 : $unit->id,
            'excise_tax'    => $row['excise_tax'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccExciseImport();
          $data->setData($arr);
        return new AccExcise($arr);
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
