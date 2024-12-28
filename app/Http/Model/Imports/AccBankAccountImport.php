<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccBankAccount;
use App\Http\Model\AccBank;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccBankAccountImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
        $bank = AccBank::WhereDefault('code',$row['bank'])->first();
        $code_check = AccBankAccount::WhereCheck('bank_account',$row['bank_account'],'id',null)->first();
        if($code_check == null){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'bank_account'    => $row['bank_account'],
            'bank_name'    => $row['bank_name'],
            'bank_id'    => $bank == null ? 0 : $bank->id,
            'branch'    => $row['branch'],
            'description'    => $row['description'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccBankAccountImport();
          $data->setData($arr);
        return new AccBankAccount($arr);
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
