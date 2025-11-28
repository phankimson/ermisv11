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
use App\Http\Model\AccObject;
use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccCurrency;
use App\Http\Model\AccSystems;
use App\Http\Model\AccStock;
use App\Classes\Convert;
use App\Http\Model\AccSuppliesGoods;
use Illuminate\Support\Facades\Auth;

class AccInventoryReceiptImport implements  WithHeadingRow, WithMultipleSheets
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
    
       public function setDataCrit($arr)
     {
         array_push(self::$crit,$arr);
     }  
     public function getData()
    {
       $data['general'] = self::$first;
       $data['detail'] = self::$second;
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


class FirstSheetValImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit
{   
    protected $type;
    protected $group;
    function __construct($menu,$group) { //this will NOT overwrite the parents construct
       $this->type = $menu;
       $this->group = $group;
    }
    
    public function model(array $row)
    {
      $data = new AccInventoryReceiptImport($this->type,$this->group);
      $currency_default = $data->getCurrencyDefault();
      $subject = AccObject::WhereDefault('code',$row['subject'])->first();
      $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
      $currency = AccCurrency::WhereDefault('code',$row['currency'])->first(); 
      $voucher_date = $row['voucher_date'] ? Convert::DateExcel($row['voucher_date']) : date("Y-m-d");
      $accounting_date = $row['accounting_date'] ? Convert::DateExcel($row['accounting_date']) : date("Y-m-d");
      $rate = $row['rate'] ? $row['rate'] : $currency_default->rate;
      $total_amount_rate = $row['total_amount_rate'] ? $row['total_amount_rate'] : $row['total_amount'] * $rate;
      $user = Auth::user();
      
      $type = $this->type; // Receipt Inventory
        if($code_check == null && $row['voucher']){      
          $arr = [
            'id'     => Str::uuid()->toString(),
            'type'   => $type,
            'voucher'    => $row['voucher'],
            'description'    => $row['description'],
            'voucher_date'    => $voucher_date,
            'accounting_date'    => $accounting_date,
            'currency' => $currency == null ? $currency_default->id : $currency->id,
            'traders'    => $row['traders'],
            'subject'    => $subject == null ? 0 : $subject->id,
            'total_quantity'    => $row['total_quantity'],
            'total_amount'    => $row['total_amount'],
            'rate'    => $rate,
            'total_amount_rate'    => $total_amount_rate,    
            'group'=>  $this->group,   
            'user' => $user->id,
            'status'    => $row['status'] == null ? 1 : $row['status'], 
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new AccInventoryReceiptImport($this->type,$this->group);
          $data->setDataFirst($arr);
          return new AccGeneral($arr);
      }
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
         return (int) config('excel.setting.HEADING_ROW');
     }
}

class SecondSheetImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow, WithBatchInserts, WithLimit
{
    public function model(array $row)
    {
        $data = new AccInventoryReceiptImport("",""); 
        $item = AccSuppliesGoods::WhereDefault('code',$row['item_code'])->first();     
        $credit = AccAccountSystems::WhereDefault('code',$row['credit'])->first();    
        $debit = AccAccountSystems::WhereDefault('code',$row['debit'])->first();
        $debit_import = $debit == null ? $item->stock_account : $debit->id;
        if($item != null && $row['item_code'] && $credit != null && $debit_import != null){             
            $stock = AccStock::WhereDefault('code',$row['stock'])->first(); 
            $currency_default = $data->getCurrencyDefault();
            $general = AccGeneral::WhereDefault('voucher',$row['voucher'])->first();
           
            $subject_credit = AccObject::WhereDefault('code',$row['subject'])->first();        
            $currency = AccCurrency::WhereDefault('code',$row['currency'])->first();
            $price = $row['price'] ? $row['price'] : $item->price_purchase;
            $amount = $row['amount'] ? $row['amount'] : $row['quantity']*$price;
            $rate = $row['rate'] ? $row['rate'] : $currency_default->rate;
            $amount_rate = $row['amount_rate'] ? $row['amount_rate'] :  $amount * $rate;
            $detail_id = Str::uuid()->toString();
            $arr = [
              'id'     => $detail_id,
              'general_id'    => $general == null ? 0 : $general->id,
              'description'    => $row['item_name'] ? $row['item_name'] : $item->code." - ". $item->name,
              'currency' => $currency == null ? $currency_default->id : $currency->id,
              'debit'    => $debit_import,
              'credit'    => $credit == null ? 0 : $credit->id,
              'subject_id_credit'    => $subject_credit == null ? 0 : $subject_credit->id,
              'subject_name_credit'    => $subject_credit == null ? 0 : $subject_credit->code." - ".$subject_credit->name,
              'amount'    => $amount,   
              'rate'    => $rate,
              'amount_rate'    => $amount_rate ,          
              'status'    => $row['status'] == null ? 1 : $row['status'], 
              'active'    => $row['active'] == null ? 1 : $row['active'],
            ];

             $arr_crit = [
                'id'     => Str::uuid()->toString(),
                'general_id'    => $general == null ? 0 : $general->id,
                'detail_id'    => $detail_id,
                'acc'    => $debit == null ? $item->stock_account : $debit->id,
                'item_id' => $item->id,
                'item_code' => $item->code,
                'item_name' => $item->name,
                'unit' => $item->unit_id,
                'stock' => $stock == null ? $item->stock_default : $stock->id,
                'quantity'    => $row['quantity'],  
                'price'    => $price,  
                'amount'    => $amount,             
              ];
        }     

        $data = new AccInventoryReceiptImport("","");
        $data->setDataSecond($arr);
        $data->setDataCrit($arr_crit);
        return new AccDetail($arr);        
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
         return (int) config('excel.setting.HEADING_ROW');
     }
  }
