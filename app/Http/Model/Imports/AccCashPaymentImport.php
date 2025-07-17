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
use Maatwebsite\Excel\Concerns\WithStartRow;


class AccCashPaymentImport implements  WithHeadingRow, WithMultipleSheets
{ 
  protected $menu;
  public static $first = array();
  public static $second = array();
  public static $third = array();
   public function __construct($menu)
  {
      $this->menu = $menu;
  }
  public function sheets(): array
    {        
        return [
          'general' => new FirstSheetImport($this->menu),
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
         array_push(self::$second,$arr);
     }  
     public function getData()
    {
       $data['general'] = self::$first;
       $data['detail'] = self::$second;
       $data['tax'] = self::$second;
       return $data;
       //return array_merge(self::$first,self::$second,self::$third);
    }   
}


class FirstSheetImport extends AccCashPaymentImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
{   
    protected $type;
   function __construct() { //this will NOT overwrite the parents construct
       $this->type = parent::__construct();
    }
    public function model(array $row)
    {
      $subject = AccObject::WhereDefault('code',$row['subject'])->first();
      $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
      $type = $this->type; //Payment Cash
      $group = 2;
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
            'group'=>  $group,   
            'status'    => $row['status'] == null ? 1 : $row['status'], 
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccCashPaymentImport($this->menu);
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
        return env("HEADING_ROW",1);
    }
      public function startRow(): int
    {
        return env("START_ROW",2);
    }
}

class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
      $data = new AccCashPaymentImport("");
      $data->setDataSecond($arr);
      return new AccDetail($arr);          
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
          'rate'    => $row['rate'],   
          'total_amount_rate'    => $row['total_amount_rate'],
          'payment'    => 0,      
          'status'    => $row['status'] == null ? 1 : $row['status'], 
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];      
        $data = new AccCashPaymentImport("");
        $data->setDataThird($arr);
        return new AccVatDetail($arr);
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

