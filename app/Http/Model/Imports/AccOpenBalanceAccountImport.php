<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccOpenBalanceAccountImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $account_system = AccAccountSystems::WhereDefault('code',$row['code'])->first();
        if($account_system != null && $row['code']){
          $arr = [
            'id'     => $account_system->id,
            'balance_id'    => 0,
            'period'    => 0,
            'debit_balance'    => $row['debit_balance'],
            'credit_balance'    => $row['credit_balance'],
          ];
          $data = new AccOpenBalanceAccountImport();
          $data->setData($arr);
        return;
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
