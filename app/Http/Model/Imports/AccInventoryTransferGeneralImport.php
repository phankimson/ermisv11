<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccSystems;
use App\Classes\Convert;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccStock;
use App\Http\Traits\NumberVoucherTraits;

class AccInventoryTransferGeneralImport implements WithMappedCells,ToModel
{
  use NumberVoucherTraits;
  protected $menu;
  public static $data = array();
  public function __construct($menu)
  {
      $this->menu = $menu;
  }
  public function mapping(): array
  {
      return [
              'stock_issue'  => 'C3',
              'stock_receipt' => 'C4',
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
    $data = new AccInventoryTransferGeneralImport($this->menu);
    $currency_default = $data->getCurrencyDefault();                   
    $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
    $currency = AccCurrency::WhereDefault('code',$row['currency'])->first();
    $stock_issue = AccStock::WhereDefault('code',$row['stock_issue'])->first();
    $stock_receipt = AccStock::WhereDefault('code',$row['stock_receipt'])->first();
    if(is_numeric($row['accounting_date'])){
      $row['accounting_date'] =  $row['accounting_date'] ? Convert::DateExcel($row['accounting_date']):date("Y-m-d");
    }else{
      $row['accounting_date'] = date("Y-m-d");
    }
    if(is_numeric($row['voucher_date'])){
      $row['voucher_date'] = $row['voucher_date'] ? Convert::DateExcel($row['voucher_date']):date("Y-m-d");
    }else{
      $row['voucher_date'] = date("Y-m-d");
    }
    if($code_check == null){
      if(!$row['voucher']){      
           // Load Phiếu tự động / Load AutoNumber
           $row['voucher'] =  $this->loadMaskerNumberVoucher($this->menu,$row);        
        }
      }  
       
      $arr =  [
        'voucher'    => $row['voucher'],
        'description'    => $row['description'],
        'voucher_date'    => $row['accounting_date'],
        'accounting_date'    => $row['voucher_date'],
        'currency'   => $currency?$currency->id:$currency_default->id,
        'rate'      =>$row['rate'],
        'stock_issue'    => optional($stock_issue)->id,
        'stock_receipt'    => optional($stock_receipt)->id,
      ]; 
      AccInventoryTransferGeneralImport::setData($arr);     
      return ;
   }

}