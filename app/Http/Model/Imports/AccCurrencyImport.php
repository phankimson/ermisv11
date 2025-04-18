<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccCurrency;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccCurrencyImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
        $arr['denominations'] = array();
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
        $code_check = AccCurrency::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
            $arr = [
                'id'     => Str::uuid()->toString(),
               'code'    => $row['code'],
               'name'    => $row['name'],
               'name_en'    => $row['name_en'],
               'conversion_calculation'    => $row['conversion_calculation'],
               'rate'    => $row['rate'],
               'conversion_rate_vi'    => $row['conversion_rate_vi'],
               'conversion_rate_en'    => $row['conversion_rate_en'],
               'currency_1_vi'    => $row['currency_1_vi'],
               'currency_1_en'    => $row['currency_1_en'],
               'currency_2_vi'    => $row['currency_2_vi'],
               'currency_2_en'    => $row['currency_2_en'],
               'currency_3_vi'    => $row['currency_3_vi'],
               'currency_3_en'    => $row['currency_3_en'],
               'account_bank'    => $row['account_bank'],
               'account_cash'    => $row['account_cash'],
               'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
            $data = new AccCurrencyImport();
            $data->setData($arr);
        return new AccCurrency($arr);
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
