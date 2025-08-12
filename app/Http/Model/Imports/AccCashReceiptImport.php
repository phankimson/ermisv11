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
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSystems;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Classes\Convert;


class AccCashReceiptImport implements  WithHeadingRow, WithMultipleSheets
{ 
  protected $menu;
  protected $group;
  public static $first = array();
  public static $second = array();
  public static $third = array();
  public static $crit = array();

     public function __construct($menu,$group)
  {
      $this->menu = $menu;
      $this->group = $group;
  }

  public function sheets(): array
    {        
        return [
          'general' => new FirstSheetValImport($this->menu,$this->group),
          'detail' => new SecondSheetImport(),
          'tax' => new ThirdSheetImport(),
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */


  
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
         array_push(self::$third,$arr);
     }  
         public function setDataCrit($arr)
     {
         array_push(self::$crit,$arr);
     }  
     public function getData()
    {
       $data['general'] = self::$first;
       $data['detail'] = self::$second;
       $data['tax'] = self::$third;
       $data['crit'] = self::$crit;
       return $data;
       //return array_merge(self::$first,self::$second,self::$third);
    }   
        public function getCurrencyDefault()
    {
       $default = AccSystems::get_systems("CURRENCY_DEFAULT");  
       $currency_default = AccCurrency::get_code($default->value);
       return $currency_default;
    } 
    
}


class FirstSheetValImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{   
    protected $type;
    protected $group;
    function __construct($menu,$group) { //this will NOT overwrite the parents construct
       $this->type = $menu;
       $this->group = $group;
    }   

    public function model(array $row)
    {
      $data = new AccCashReceiptImport($this->type,$this->group); 
      $currency_default = $data->getCurrencyDefault();
      $subject = AccObject::WhereDefault('code',$row['subject'])->first();
      $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
      $currency = AccCurrency::WhereDefault('code',$row['currency'])->first(); 
      $voucher_date = $row['voucher_date'] ? Convert::DateExcel($row['voucher_date']) : date("Y-m-d");
      $accounting_date = $row['accounting_date'] ? Convert::DateExcel($row['accounting_date']) : date("Y-m-d");
      $type = $this->type; //Payment Cash
        if($code_check == null && $row['voucher']){         
          $arr = [
            'id'     => Str::uuid()->toString(),
            'type'   => $type,
            'voucher'    => $row['voucher'],
            'description'    => $row['description'],
            'voucher_date'    => $voucher_date,
            'accounting_date'    => $accounting_date,
            'currency'    => $currency == null ? $currency_default->id : $currency->id,
            'traders'    => $row['traders'],
            'subject'    => $subject == null ? 0 : $subject->id,
            'total_amount'    => $row['total_amount'],
            'rate'    => $row['rate'],
            'group'=>  $this->group,   
            'total_amount_rate'    => $row['total_amount_rate'],        
            'status'    => $row['status'] == null ? 1 : $row['status'], 
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccCashReceiptImport($this->type,$this->group);
          $data->setDataFirst($arr);
          return new AccGeneral($arr);
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
        return env("HEADING_ROW",2);
    }
      public function startRow(): int
    {
        return env("START_ROW",3);
    }
}

class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{
    public function model(array $row)
    {
        if(substr($row['debit'],0,3) === "111"){
        $data = new AccCashReceiptImport("","");
        $currency_default = $data->getCurrencyDefault();
        $general = AccGeneral::WhereDefault('voucher',$row['voucher'])->first();
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
        $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();
        $subject_credit = AccObject::WhereDefault('code',$row['subject'])->first();
        $department = AccDepartment::WhereDefault('code',$row['department'])->first();
        $bank_account = AccBankAccount::WhereDefault('bank_account',$row['bank_account'])->first();
        $currency = AccCurrency::WhereDefault('code',$row['currency'])->first();
        $arr = [
          'id'     => Str::uuid()->toString(),
          'general_id'    => $general == null ? 0 : $general->id,
          'description'    => $row['description'],
          'debit'    => $debit == null ? 0 : $debit->id,
          'credit'    => $credit == null ? 0 : $credit->id,
          'currency' => $currency == null ? $currency_default->id : $currency->id,
          'subject_id_credit'    => $subject_credit == null ? 0 : $subject_credit->id,
          'subject_name_credit'    => $subject_credit == null ? 0 : $subject_credit->code." - ".$subject_credit->name,
          'amount'    => $row['amount'],
          'rate'    => $row['rate'],
          'amount_rate'    => $row['amount_rate'],       
          'department'    => $department == null ? 0 : $department->id,
          'bank_account_credit'    => $bank_account == null ? 0 : $bank_account->id,         
          'status'    => $row['status'] == null ? 1 : $row['status'], 
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $arr_crit = [
          'debit'    => $row['debit'],
          'debit_id'    => $debit == null ? 0 : $debit->id,
          'credit'    => $row['credit'],
          'credit_id'    => $credit == null ? 0 : $credit->id,
          'currency' => $currency == null ? $currency_default->id : $currency->id,
          'bank_account' => $bank_account == null ? 0 : $bank_account->id,     
          'amount'    => $row['amount'],
          'rate'    => $row['rate'],
          'amount_rate'    => $row['amount_rate'],  
        ];

        $data = new AccCashReceiptImport("","");
        $data->setDataSecond($arr);
        $data->setDataCrit($arr_crit);
        return new AccDetail($arr);    
      }else{
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

  class ThirdSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
  {
      public function model(array $row)
      {
        $general = AccGeneral::WhereDefault('voucher',$row['voucher'])->first();
        $subject = AccObject::WhereDefault('code',$row['subject'])->first();
        $date_invoice = $row['date_invoice'] ? Convert::DateExcel($row['date_invoice']) : date("Y-m-d");
        $arr_check = array(
                  ['invoice', '=',$row['invoice']],
                  ['invoice_symbol', '=',$row['invoice_symbol']],
                  //['invoice_form', '=',$row['invoice_form']],
                  ['tax_code', '=',$row['tax_code']]
                );
        $tax_check = AccVatDetail::get_invoice($arr_check);
        if(!$tax_check){
        $arr =  [
          'id'     => Str::uuid()->toString(),
          'general_id'    => $general == null ? 0 : $general->id,
          'description'    => $row['description'],
          'date_invoice'    => $date_invoice,
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
          'rate'    => $row['rate'],   
          'total_amount_rate'    => $row['total_amount_rate'],
          'payment'    => 1,           
          'status'    => $row['status'] == null ? 1 : $row['status'], 
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];      
        $data = new AccCashReceiptImport("","");
        $data->setDataThird($arr);
        return new AccVatDetail($arr);
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

