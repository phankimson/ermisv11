<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Str;
use App\Http\Model\AccDetail;
use App\Http\Model\AccVatDetail;
use App\Http\Model\AccObject;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccDepartment;
use App\Http\Model\AccBankAccount;

class AccCashReceiptVoucherImport implements  WithHeadingRow, WithBatchInserts, WithLimit, WithMultipleSheets
{
  protected $general_id;
  public function sheets(): array
    {
        return [
          'detail' => new FirstSheetImport(),
          'tax' => new SecondSheetImport(),
        ];
    }

    public function  __construct($general_id)
    {
        $this->general_id= $general_id;
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

}

class FirstSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow
{
    public function model(array $row)
    {
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
      $subject_credit = AccObject::WhereDefault('code',$row['subject_credit'])->first();
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
      return new AccDetail([
         'id'     => Str::uuid()->toString(),
         'general_id'    =>  $this->general_id,
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
     ]);
    }

    public function startRow(): int
    {
        return 10;
    }
  }

  class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow
  {
      public function model(array $row)
      {
        $subject = AccObject::WhereDefault('code',$row['subject'])->first();       
      return new AccVatDetail([
           'id'     => Str::uuid()->toString(),
           'general_id'    =>  $this->general_id,
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
       ]);
      }
      public function startRow(): int
    {
        return 10;
    }
  }

