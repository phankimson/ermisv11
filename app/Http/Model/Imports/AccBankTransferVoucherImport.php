<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccBankAccount;
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSettingVoucher;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class AccBankTransferVoucherImport implements  WithHeadingRow, WithMultipleSheets
{
  protected $menu;
  public static $first = array();
  public static $second = array();
  

    public function __construct($menu)
  {
      $this->menu = $menu;
  }

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
          'detail' => new FirstSheetCritImport($this->menu),
        ];
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

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

}

class FirstSheetCritImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit
{
    protected $menu;
    function __construct($menu) { //this will NOT overwrite the parents construct
       $this->menu = $menu;
    }   

    public function model(array $row)
    {
      if($row['description'] && $row['no']){
      $data = new AccBankTransferVoucherImport($this->menu);
      $currency_default = $data->getCurrencyDefault();
      $setting_default = $data->getSettingDefault();
        if(substr($row['debit'],0,3) === "112"){
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
        }else{
          $debit = AccAccountSystems::find($setting_default->debit);
        }
        if(substr($row['debit'],0,3) === "112"){
        $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first(); 
        }else{
        $credit = AccAccountSystems::find($setting_default->credit);
        }

      $bank_account_debit = AccBankAccount::WhereDefault('bank_account',$row['bank_account_debit'])->first();  
      $bank_account_credit = AccBankAccount::WhereDefault('bank_account',$row['bank_account_credit'])->first();
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
        'bank_account_debit'    => $bank_account_debit ? BankDropDownResource::make($bank_account_debit) : DefaultDropDownResource::make(""),
        'bank_account_credit'    => $bank_account_credit ? BankDropDownResource::make($bank_account_credit) : DefaultDropDownResource::make(""),     
      ];
      $data = new AccBankTransferVoucherImport($this->menu);
      $data->setDataFirst($arr);
      }      
      return;
    }

      public function batchSize(): int
    {
      return (int) config('excel.setting.IMPORT_SIZE');
    }   
  
     public function limit(): int
     {
      return (int) config('excel.setting.IMPORT_LIMIT');
     }

    public function headingRow(): int
    {
        return 9;
    }

  
  }

