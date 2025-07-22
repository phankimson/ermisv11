<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class AccBankTransferVoucherImport implements  WithHeadingRow, WithMultipleSheets
{
  public static $first = array();
  public static $second = array();
  
  public function setDataFirst($arr)
  {
      array_push(self::$first,$arr);
  }   
  

   public function getData()
   {
      $data['detail'] = self::$first;
      return $data;
   }   

  public function sheets(): array
    {
        return [
          'detail' => new FirstSheetImport(),
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

}

class FirstSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow, WithBatchInserts, WithLimit
{
    public function model(array $row)
    {
      if($row['description'] && $row['no']){
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first(); 
      $bank_account_debit = AccBankAccount::WhereDefault('bank_account_debit',$row['bank_account_debit'])->first();  
      $bank_account_credit = AccBankAccount::WhereDefault('bank_account_credit',$row['bank_account_credit'])->first();
      $row['amount'] = $row['amount'] == null ? 0 : $row['amount'];
      $row['rate'] = $row['rate'] == null ? 0 : $row['rate'];
      $arr = [
        'description'    => $row['description'],
        'debit'    =>  $debit ? LangDropDownResource::make($debit) : DefaultDropDownResource::make($debit),
        'credit'    => $credit ? LangDropDownResource::make($credit) : DefaultDropDownResource::make($credit),
        'amount'    => $row['amount'],
        'rate'    => $row['rate'],
        'amount_rate'    => $row['amount_rate']== null ? $row['amount']*$row['rate'] : $row['amount_rate'],    
        'accounted_fast'    => DefaultDropDownResource::make(null),       
        'bank_account_debit'    => $bank_account_debit ? BankDropDownResource::make($bank_account_debit) : DefaultDropDownResource::make($bank_account_debit),
        'bank_account_credit'    => $bank_account_credit ? BankDropDownResource::make($bank_account_credit) : DefaultDropDownResource::make($bank_account_credit),       
      ];
      $data = new AccBankTransferVoucherImport();
      $data->setDataFirst($arr);
      }      
      return;
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
        return 9;
    }

    public function startRow(): int
    {
        return 10;
    }
  }

