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
use App\Http\Model\AccDepartment;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccVat;
use App\Http\Model\AccWorkCode;

class AccCashReceiptVoucherImport implements  WithHeadingRow, WithBatchInserts, WithLimit, WithMultipleSheets
{
  public static $first = array();
  public static $second = array();
  public function setDataFirst($arr)
  {
      array_push(self::$first,$arr);
  }   
  
   public function setDataSecond($arr)
   {
       array_push(self::$second,$arr);
   }    

   public function getData()
   {
      $data['detail'] = self::$first;
      $data['tax'] = self::$second;
      return $data;
   }   

  public function sheets(): array
    {
        return [
          'detail' => new FirstSheetImport(),
          'tax' => new SecondSheetImport(),
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

}

class FirstSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow
{
    public function model(array $row)
    {
      if($row['description'] && $row['no']){
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
      $cost_code = AccCostCode::WhereDefault('code',$row['cost_code'])->first();
      $case_code = AccCaseCode::WhereDefault('code',$row['case_code'])->first();
      $statistical_code = AccStatisticalCode::WhereDefault('code',$row['statistical_code'])->first();
      $work_code = AccWorkCode::WhereDefault('code',$row['work_code'])->first(); 
      $row['amount'] = $row['amount'] == null ? 0 : $row['amount'];
      $row['rate'] = $row['rate'] == null ? 0 : $row['rate'];
      $arr = [
        'description'    => $row['description'],
        'debit'    => $debit == null ? 0 : $debit->id,
        'credit'    => $credit == null ? 0 : $credit->id,
        'amount'    => $row['amount'],
        'rate'    => $row['rate'],
        'amount_rate'    => $row['amount_rate']== null ? $row['amount']*$row['rate'] : $row['amount_rate'],       
        'department'    => $department == null ? 0 : $department->id,
        'bank_account'    => $bank_account == null ? 0 : $bank_account->id, 
        'cost_code'    => $cost_code == null ? 0 : $cost_code->id, 
        'case_code'    => $case_code == null ? 0 : $case_code->id, 
        'statistical_code'    => $statistical_code == null ? 0 : $statistical_code->id, 
        'work_code'    => $work_code == null ? 0 : $work_code->id, 
        'lot_number'    => $row['lot_number'], 
        'contract'    => $row['contract'], 
        'order'    => $row['order'], 
      ];
      $data = new AccCashReceiptVoucherImport();
      $data->setDataFirst($arr);
      }      
      return;
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

  class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow
  {
      public function model(array $row)
      {
        if($row['description'] && $row['no']){
        $vat_tax = AccVat::WhereDefault('vat_tax',$row['vat_tax'])->first();
        $row['amount'] = $row['amount'] == null ? 0 : $row['amount'];
        $row['vat_tax'] = $row['vat_tax'] == null ? 0 : $row['vat_tax'];
        $arr = [
          'description'    => $row['description'],
          'date_invoice'    => $row['date_invoice'],
          'invoice_form'    => $row['invoice_form'],
          'invoice_symbol'    => $row['invoice_symbol'],
          'invoice'    => $row['invoice'],
          'address'    => $row['address'],
          'tax_code'    => $row['tax_code'],
          'vat_type'    => $row['vat_type'],          
          'amount'    => $row['amount'],
          'vat_tax'    => $vat_tax == null ? 0 : $vat_tax->id, 
          'total_amount'    => $row['total_amount']== null ? $row['amount']*$row['vat_tax'] : $row['total_amount'],       
        ];    
        $data = new AccCashReceiptVoucherImport();
        $data->setDataSecond($arr);
      }
        return;
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

