<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccExcise;
use App\Http\Model\AccUnit;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccOpenBalanceAccountImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
        if($account_system != null){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'period'    => 0,
            'account_systems'    => $account_system->id,
            'debit_close'    => $row['debit_balance'],
            'credit_close'    => $row['credit_balance'],
          ];
          $data = new AccOpenBalanceAccountImport();
          $data->setData($arr);
        return;
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

     
     public function headingRow(): int
     {
         return env("HEADING_ROW",1);
     }
       public function startRow(): int
     {
         return env("START_ROW",2);
     }
}
