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
        dd($row);
        $account_system = AccAccountSystems::WhereDefault('code',$row['code'])->first();
        if($account_system != null && $row['code']){
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
