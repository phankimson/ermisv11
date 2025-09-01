<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccNaturalResources;
use App\Http\Model\AccUnit;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccNaturalResourcesImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $parent = AccNaturalResources::WhereDefault('code',$row['parent'])->first();
        $code_check = AccNaturalResources::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'parent_id'    => $parent == null ? $parent : $parent->id,
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'unit_id'    => $unit == null ? 0 : $unit->id,
            'natural_tax'    => $row['natural_tax'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccNaturalResourcesImport();
          $data->setData($arr);
        return new AccNaturalResources($arr);
      }
    }

    public function batchSize(): int
    {
      return (int) config('excel.setting.IMPORT_SIZE');
    }   
  
     public function limit(): int
     {
      return (int) config('excel.setting.IMPORT_LIMIT');
     }
     
     public function headingRow(): int
     {
         return (int) config('excel.setting.HEADING_ROW');
     }    

}
