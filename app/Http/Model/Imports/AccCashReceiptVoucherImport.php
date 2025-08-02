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
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSettingVoucher;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;
use App\Classes\Convert;

class AccCashReceiptVoucherImport implements  WithHeadingRow, WithMultipleSheets
{
    protected $menu;
  public static $first = array();
  public static $second = array();
  
   function __construct($menu) { //this will NOT overwrite the parents construct
       $this->menu = $menu;
    }   
  
  
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

   
     public function getCurrencyDefault()
    {
       $default = AccSystems::get_systems("CURRENCY_DEFAULT");
       $currency_default = AccCurrency::get_code($default->value);
       return $currency_default;
    } 

     public function getSettingDefault()
    {
       $setting_voucher = AccSettingVoucher::get_menu($this->menu->id);
       return $setting_voucher;
    } 

  public function sheets(): array
    {
        return [
          'detail' => new FirstSheetCritImport($this->menu),
          'tax' => new SecondSheetImport(),
        ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

}

class FirstSheetCritImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow, WithBatchInserts, WithLimit
{
   protected $menu;
    function __construct($menu) { //this will NOT overwrite the parents construct
       $this->menu = $menu;
    }   

    public function model(array $row)
    {
      if($row['description'] && $row['no']){
       $data = new AccCashPaymentVoucherImport($this->menu);
      $currency_default = $data->getCurrencyDefault();
      $setting_default = $data->getSettingDefault();
      if(substr($row['debit'],0,3) === "111"){
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      }else{
        $debit = AccAccountSystems::find($setting_default->debit);
      }
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
        'debit'    =>  $debit ? LangDropDownResource::make($debit) : DefaultDropDownResource::make(""),
        'credit'    => $credit ? LangDropDownResource::make($credit) : DefaultDropDownResource::make(""),
        'amount'    => $row['amount'],
        'currency'    => $currency_default != null ? $currency_default->id : 0,
        'rate'    => $row['rate'],
        'amount_rate'    => $row['amount_rate']== null ? $row['amount']*$row['rate'] : $row['amount_rate'],    
        'accounted_fast'    => DefaultDropDownResource::make(null),
        'subject_code'    => DefaultDropDownResource::make(null),      
        'department'    => $department ? LangDropDownResource::make($department) : DefaultDropDownResource::make(""),
        'bank_account'    => $bank_account ? BankDropDownResource::make($bank_account) : DefaultDropDownResource::make(""),
        'cost_code'    => $cost_code ? LangDropDownResource::make($cost_code) : DefaultDropDownResource::make(""),
        'case_code'    => $case_code ? LangDropDownResource::make($case_code) : DefaultDropDownResource::make(""),
        'statistical_code'    => $statistical_code ? LangDropDownResource::make($statistical_code) : DefaultDropDownResource::make(""),
        'work_code'    => $statistical_code ? LangDropDownResource::make($work_code) : DefaultDropDownResource::make(""),
        'lot_number'    => $row['lot_number'], 
        'contract'    => $row['contract'], 
        'order'    => $row['order'], 
      ];
      $data = new AccCashReceiptVoucherImport($this->menu);
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

  class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithStartRow, WithBatchInserts, WithLimit
  {
      public function model(array $row)
      {
        if($row['description'] && $row['no']){
        $vat_type = AccVat::WhereDefault('code',$row['vat_type'])->first();
        $row['amount'] = $row['amount'] == null ? 0 : $row['amount'];  
        $date_invoice = $row['date_invoice'] ? Convert::DateExcel($row['date_invoice']) : date("Y-m-d");
        $arr = [
          'description'    => $row['description'],
          'date_invoice'    =>  $date_invoice,
          'invoice_form'    => $row['invoice_form'],
          'invoice_symbol'    => $row['invoice_symbol'],
          'invoice'    => $row['invoice'],
          'address'    => $row['address'],
          'tax_code'    => $row['tax_code'],
          'vat_type'    => $vat_type ? LangDropDownResource::make($vat_type) : DefaultDropDownResource::make($vat_type), 
          'vat_tax' => $vat_type ? $vat_type->vat_tax : 0,    
          'amount'    => $row['amount'],
          'tax'    => $row['tax'],
          'tax_amount'    => $row['total_amount']== null ? $row['amount'] + $row['tax'] : $row['total_amount'],    
          'tax_rate'    => $row['rate'],   
          'tax_amount_rate'    => $row['total_amount_rate']== null ? $row['total_amount']*$row['rate'] : $row['total_amount_rate'],  
        ];    
        $data = new AccCashReceiptVoucherImport("");
        $data->setDataSecond($arr);
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

