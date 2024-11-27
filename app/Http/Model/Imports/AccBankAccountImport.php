<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccBankAccount;
use App\Http\Model\AccBank;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccBankAccountImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading, WithLimit
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
        return 200;
    }
 
     public function chunkSize(): int
    {
        return 200;
    }
 
    public function limit(): int
    {
        return 1000;
    }

}
