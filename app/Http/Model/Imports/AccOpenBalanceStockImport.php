<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccSuppliesGoods;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccOpenBalanceStockImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $suppler_goods = AccSuppliesGoods::WhereDefault('code',$row['code'])->first();
        if($suppler_goods != null && $row['code']){
          $arr = [
            'id'     => $suppler_goods->id,
            'balance_id'    => 0,
            'period'    => 0,
            'account_default'    => $suppler_goods->stock_account,
            'quantity'    => Convert::intDefaultformat($row['quantity']),
            'amount'    => Convert::intDefaultformat($row['amount']),
          ];
          $data = new AccOpenBalanceStockImport();
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
