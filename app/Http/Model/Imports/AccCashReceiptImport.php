<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Illuminate\Support\Str;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccDetail;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccObject;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccBankAccount;


class AccCashReceiptImport implements  WithHeadingRow, WithBatchInserts, WithLimit, WithMultipleSheets
{ 
  public static $first = array();
  public static $second = array();
  public static $third = array();
  public function sheets(): array
    {        
        return [
          'general' => new FirstSheetImport(),
          'detail' => new SecondSheetImport(),
          'tax' => new ThirdSheetImport(),
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */


    public function batchSize(): int
    {
      return env("IMPORT_SIZE",100);
    }   
  
     public function limit(): int
     {
      return env("IMPORT_LIMIT",200);
     }
     
   public function setDataFirst($arr)
   {
       array_push(self::$first,$arr);
   }   
   
    public function setDataSecond($arr)
    {
        array_push(self::$second,$arr);
    }    
     public function setDataThird($arr)
     {
         array_push(self::$second,$arr);
     }  
     public function getData()
    {
       return array_merge(self::$first,self::$second,self::$third);
    }   
}


class FirstSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow
{   


    public function model(array $row)
    {
      $subject = AccObject::WhereDefault('code',$row['subject'])->first();
      $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
      $type = 1; // 1 Receipt Cash
        if($code_check == null){      
          $arr = [
            'id'     => Str::uuid()->toString(),
            'type'   => $type,
            'voucher'    => $row['voucher'],
            'description'    => $row['description'],
            'voucher_date'    => $row['voucher_date'],
            'accounting_date'    => $row['accounting_date'],
            'currency'    => $row['currency'] == null ? 1 : $row['currency'],
            'traders'    => $row['traders'],
            'subject'    => $subject == null ? 0 : $subject->id,
            'total_amount'    => $row['total_amount'],
            'rate'    => $row['rate'],
            'total_amount_rate'    => $row['total_amount_rate'],        
            'status'    => $row['status'] == null ? 1 : $row['status'], 
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccCashReceiptImport();
          $data->setDataFirst($arr);
          return new AccGeneral($arr);
      }
    }    
}

class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow
{
    public function model(array $row)
    {
      $general = AccGeneral::WhereDefault('voucher',$row['voucher'])->first();
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
      $subject_credit = AccObject::WhereDefault('code',$row['subject_credit'])->first();
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
      $arr = [
        'id'     => Str::uuid()->toString(),
        'general_id'    => $general == null ? 0 : $general->id,
        'description'    => $row['description'],
        'debit'    => $debit == null ? 0 : $debit->id,
        'credit'    => $credit == null ? 0 : $credit->id,
        'subject_credit'    => $subject_credit == null ? 0 : $subject_credit->id,
        'amount'    => $row['amount'],
        'rate'    => $row['rate'],
        'amount_rate'    => $row['amount_rate'],       
        'department'    => $department == null ? 0 : $department->id,
        'bank_account'    => $bank_account == null ? 0 : $bank_account->id,         
        'status'    => $row['status'] == null ? 1 : $row['status'], 
        'active'    => $row['active'] == null ? 1 : $row['active'],
      ];
      $data = new AccCashReceiptImport();
      $data->setDataSecond($arr);
      return new AccDetail($arr);          
    }
  }

  class ThirdSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow
  {
      public function model(array $row)
      {
        $general = AccGeneral::WhereDefault('voucher',$row['voucher'])->first();
        $subject = AccObject::WhereDefault('code',$row['subject'])->first();
        $arr =  [
          'id'     => Str::uuid()->toString(),
          'general_id'    => $general == null ? 0 : $general->id,
          'description'    => $row['description'],
          'date_invoice'    => $row['date_invoice'],
          'invoice_form'    => $row['invoice_form'],
          'invoice_symbol'    => $row['invoice_symbol'],
          'invoice'    => $row['invoice'],
          'subject_code'    => $row['subject'],
          'subject_name'    => $subject == null ? 0 : $subject->name,
          'address'    => $row['address'],
          'tax_code'    => $row['tax_code'],
          'amount'    => $row['amount'],
          'tax'    => $row['tax'],
          'total_amount'    => $row['total_amount'],        
          'status'    => $row['status'] == null ? 1 : $row['status'], 
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];      
        $data = new AccCashReceiptImport();
        $data->setDataThird($arr);
        return new AccVatDetail($arr);
      }
  }

