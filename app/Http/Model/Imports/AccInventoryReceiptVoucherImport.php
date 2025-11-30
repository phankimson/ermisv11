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
use App\Http\Model\AccCaseCode;
use App\Http\Model\AccCostCode;
use App\Http\Model\AccStatisticalCode;
use App\Http\Model\AccWorkCode;
use App\Http\Model\AccSystems;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSettingVoucher;
use App\Http\Model\AccStock;
use App\Http\Model\AccSuppliesGoods;
use App\Http\Resources\LangDropDownResource;
use App\Http\Resources\BankDropDownResource;
use App\Http\Resources\DefaultDropDownResource;

class AccInventoryReceiptVoucherImport implements  WithHeadingRow, WithMultipleSheets
{
  protected $menu;
  public static $first = array();
    
   function __construct($menu) { //this will NOT overwrite the parents construct
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
          'detail' => new FirstSheetCritImport($this->menu)
        ];
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
      if($row['item_code'] && $row['no']){
      $data = new AccInventoryReceiptVoucherImport($this->menu);
      $currency_default = $data->getCurrencyDefault();
      $setting_default = $data->getSettingDefault();
      $item = AccSuppliesGoods::WhereDefault('code',$row['item_code'])->first();
      if(substr($row['debit'],0,2) === "15"){
      $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
      }else if($item->stock_account){
      $debit = AccAccountSystems::find($item->stock_account);
      }else{
      $debit = AccAccountSystems::find($setting_default->debit);
      }
      $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();     
      $department = AccDepartment::WhereDefault('code',$row['department'])->first();
      $stock = AccStock::WhereDefault('code',$row['stock'])->first();
      $cost_code = AccCostCode::WhereDefault('code',$row['cost_code'])->first();
      $case_code = AccCaseCode::WhereDefault('code',$row['case_code'])->first();
      $statistical_code = AccStatisticalCode::WhereDefault('code',$row['statistical_code'])->first();
      $work_code = AccWorkCode::WhereDefault('code',$row['work_code'])->first(); 
      $row['quantity'] = $row['quantity'] == null ? 0 : $row['quantity'];
      $row['price'] = $row['price'] == null ? $item->price_purchase : $row['price'];
      $row['rate'] = $row['rate'] == null ? 0 : $row['rate'];
      $arr = [
        'item_code'    => $item ? LangDropDownResource::make($item) : DefaultDropDownResource::make(""),
        'debit'    =>  $debit ? LangDropDownResource::make($debit) : DefaultDropDownResource::make(""),
        'credit'    => $credit ? LangDropDownResource::make($credit) : DefaultDropDownResource::make(""),
        'currency'    => $currency_default != null ? $currency_default->id : 0,
        'quantity'    => $row['quantity'],
        'price'    => $row['price'],
        'amount'    =>  $row['amount_rate']== null ? $row['quantity']*$row['price'] : $row['amount'],
        'rate'    => $row['rate'],
        'amount_rate'    => $row['amount_rate']== null ? $row['amount']*$row['rate'] : $row['amount_rate'],    
        'accounted_fast'    => DefaultDropDownResource::make(null),
        'subject_code'    => DefaultDropDownResource::make(null),      
        'department'    => $department ? LangDropDownResource::make($department) : DefaultDropDownResource::make(""),
        'stock'    => $stock ? LangDropDownResource::make($stock) : DefaultDropDownResource::make(""),
        'cost_code'    => $cost_code ? LangDropDownResource::make($cost_code) : DefaultDropDownResource::make(""),
        'case_code'    => $case_code ? LangDropDownResource::make($case_code) : DefaultDropDownResource::make(""),
        'statistical_code'    => $statistical_code ? LangDropDownResource::make($statistical_code) : DefaultDropDownResource::make(""),
        'work_code'    => $statistical_code ? LangDropDownResource::make($work_code) : DefaultDropDownResource::make(""),
        'lot_number'    => $row['lot_number'], 
        'contract'    => $row['contract'], 
        'order'    => $row['order'], 
      ];
      $data = new AccInventoryReceiptVoucherImport($this->menu);
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


