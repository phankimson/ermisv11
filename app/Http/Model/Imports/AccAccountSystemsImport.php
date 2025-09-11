<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Classes\Convert;
use App\Http\Model\AccAccountNature;
use App\Http\Model\AccAccountType;
use App\Http\Model\Document;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccAccountSystemsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $type = AccAccountType::WhereDefault('code',$row['type'])->first();
        $nature = AccAccountNature::WhereDefault('code',$row['nature'])->first();
        $parent = AccAccountSystems::WhereDefault('code',$row['parent'])->first();
        $document = Document::WhereDefault('code',$row['document'])->first();
        $code_check = AccAccountSystems::WhereCheck1('code',$row['code'],'document_id',$document->id,'id',null)->first();
        if($code_check == null && $row['code']){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'type'   => $type == null ? 0 : $type->id,
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],
            'parent_id'    => $parent == null ? $parent : $parent->id,
            'nature'    => $nature == null ? 0 : $nature->id,
            'date_start'    =>  Convert::dateDefaultformat($row['date_start'],'Y-m-d'),
            'date_end'    => Convert::dateDefaultformat($row['date_end'],'Y-m-d'),
            'description'    => $row['description'],
            'detail_object'    => $row['detail_object'],
            'detail_bank_account'    => $row['detail_bank_account'],
            'detail_work'    => $row['detail_work'],
            'detail_cost'    => $row['detail_cost'],
            'detail_case'    => $row['detail_case'],
            'detail_statistical'    => $row['detail_statistical'],
            'detail_orders'    => $row['detail_orders'],
            'detail_contract'    => $row['detail_contract'],
            'detail_depreciation'    => $row['detail_depreciation'],
            'detail_attribution'    => $row['detail_attribution'],
            'document_id'    => $document == null ? 0 : $document->id,    
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccAccountSystemsImport();
          $data->setData($arr);
        return new AccAccountSystems($arr);
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
