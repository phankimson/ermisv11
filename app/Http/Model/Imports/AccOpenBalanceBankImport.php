<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccBankAccount;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccOpenBalanceBankImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
        if($bank_account != null && $row['bank_account']){
          $arr = [
            'id'     => $bank_account->id,
            'balance_id'    => 0,
            'period'    => 0,
            'debit_balance'    => Convert::intDefaultformat($row['debit_balance']),
            'credit_balance'    => Convert::intDefaultformat($row['credit_balance']),
          ];
          $data = new AccOpenBalanceBankImport();
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
