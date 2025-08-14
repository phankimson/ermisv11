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
use App\Http\Model\AccObject;
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrency;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class AccEntryGeneralVoucherImport implements  WithHeadingRow, WithMultipleSheets
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
      $data = new AccBankTransferVoucherImport($this->menu);
      $currency_default = $data->getCurrencyDefault();
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();      
      $subject_debit = AccObject::WhereDefault('code',$row['subject_debit'])->first();  
      $subject_credit = AccObject::WhereDefault('code',$row['subject_credit'])->first();
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
        'subject_debit'    => $subject_debit ? LangDropDownResource::make($subject_debit) : DefaultDropDownResource::make(""),    
        'subject_credit'    => $subject_credit ? LangDropDownResource::make($subject_credit) : DefaultDropDownResource::make(""),                      
      ];
      $data = new AccEntryGeneralVoucherImport($this->menu);
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

