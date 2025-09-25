<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccSuppliesGoodsType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccSuppliesGoodsTypeImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $code_check = AccSuppliesGoodsType::WhereCheck('code',$row['code'],'id',null)->first();
        $account_systems = AccAccountSystems::WhereDefault('code',$row['account_default'])->first();
        if($code_check == null && $row['code']){
        $arr =[
            'id'     => Str::uuid()->toString(),
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'account_default'    => $account_systems == null ? 0 : $account_systems->id,  
            'filter' =>   $row['filter'],        
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccSuppliesGoodsTypeImport();
        $data->setData($arr);
        return new AccSuppliesGoodsType($arr);
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
