<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountTransfer;
use App\Http\Model\AccAccountSystems;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccAccountTransferImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit 
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
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
        $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
        $code_check = AccAccountTransfer::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
        $arr = [
            'id'     => Str::uuid()->toString(),
            'code'    => $row['code'],
            'name'    => $row['name'],
            'name_en'    => $row['name_en'],  
            'debit'    => $debit == null ? 0 : $debit->id,
            'credit'    => $credit == null ? 0 : $credit->id,
            'type'    => $row['type'] == null ? 0 : $row['type'],  
            'object'    => $row['object'] == null ? 0 : $row['object'],
            'case_code'    => $row['case_code'] == null ? 0 : $row['case_code'],
            'cost_code'    => $row['cost_code'] == null ? 0 : $row['cost_code'],
            'statistical_code'    => $row['statistical_code'] == null ? 0 : $row['statistical_code'],
            'work_code'    => $row['work_code'] == null ? 0 : $row['work_code'],
            'department'    => $row['department'] == null ? 0 : $row['department'],  
            'position'    => $row['position'] == null ? 1 : $row['position'],
            'active'    => $row['active'] == null ? 1 : $row['active'],           
        ];
        $data = new AccAccountTransferImport();
        $data->setData($arr);
        return new AccAccountTransfer($arr);
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
