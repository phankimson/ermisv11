<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccObject;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccSystems;
use App\Classes\Convert;
use App\Http\Model\AccCurrency;
use App\Http\Resources\ObjectDropDownListResource;

class AccBankPaymentGeneralImport implements WithMappedCells,ToModel
{
  protected $menu;
  public static $data = array();
  public function __construct($menu)
  {
      $this->menu = $menu;
  }
  public function mapping(): array
  {
      return [
              'subject'  => 'C3',
              'traders' => 'C4',
              'description' => 'C5',
              'currency' => 'C6',
              'accounting_date' => 'K3',
              'voucher_date' => 'K4',
              'voucher' => 'K5',              
              'rate' => 'K6'
        ];
    
  } 

  public function setData($arr)
  {
    array_push(self::$data,$arr);
  }   
  
  public function getData()
  {
     return self::$data[0];
  } 
  
  public function getCurrencyDefault()
  {
      $default = AccSystems::get_systems("CURRENCY_DEFAULT");
      $currency_default = AccCurrency::get_code($default->value);
      return $currency_default;
  } 

  public function model(array $row)
  {  
    $data = new AccBankPaymentGeneralImport($this->menu);
    $currency_default = $data->getCurrencyDefault();                   
    $subject = AccObject::WhereDefault('code',$row['subject'])->first();
    $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
    $currency = AccCurrency::WhereDefault('code',$row['currency'])->first();
    if(is_numeric($row['accounting_date'])){
      $row['accounting_date'] =  $row['accounting_date'] ? Convert::DateExcel($row['accounting_date']):date("Y-m-d");
    }
    if(is_numeric($row['voucher_date'])){
      $row['voucher_date'] = $row['voucher_date'] ? Convert::DateExcel($row['voucher_date']):date("Y-m-d");
    }
    if($code_check == null){
      if(!$row['voucher']){        
        // Lưu số nhảy
        $voucher = AccNumberVoucher::get_menu($this->menu->id);        
        // Thay đổi số nhảy theo yêu cầu DD MM YY
        $voucher_id = $voucher->id;
        $voucher_length_number = $voucher->length_number;
        $format = $voucher->format;
        $prefix = $voucher->prefix;
        if($voucher->change_voucher == 1){
          $val = Convert::dateformatArr($format,$row['accounting_date']);
          $voucher = AccCountVoucher::get_count_voucher($voucher_id,$format,$val['day_format'],$val['month_format'],$val['year_format']);              
          if(!$voucher){
            $voucher = new AccCountVoucher();
            $voucher->number_voucher = $voucher_id;
            $voucher->format = $format;
            $voucher->day = $val['day_format'];
            $voucher->month = $val['month_format'];
            $voucher->year = $val['year_format'];
            $voucher->length_number = $voucher_length_number;
            $voucher->active = 1;
          }
        }  
           // Load Phiếu tự động / Load AutoNumber
           $row['voucher'] = Convert::VoucherMasker1($voucher,$prefix);           
        }
      }  
       
      $arr =  [
        'voucher'    => $row['voucher'],
        'description'    => $row['description'],
        'voucher_date'    => $row['accounting_date'],
        'accounting_date'    => $row['voucher_date'],
        'currency'   => $currency?$currency->id:$currency_default->id,
        'rate'      =>$row['rate'],
        'traders'    => $row['traders'],
        'subject_id'    => $subject == null ? 0 : $subject->id,  
        'object' => $subject == null ? "" : new ObjectDropDownListResource($subject),
      ];    
      AccBankPaymentGeneralImport::setData($arr);     
      return ;
   }

}