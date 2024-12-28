<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccObject;
use App\Http\Model\AccCountVoucher;
use App\Classes\Convert;
use App\Http\Resources\ObjectDropDownListResource;

class AccCashReceiptGeneralImport implements WithMappedCells,ToModel
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
              'accounting_date' => 'K3',
              'voucher_date' => 'K4',
              'voucher' => 'K5'
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

  public function model(array $row)
  {             
    $subject = AccObject::WhereDefault('code',$row['subject'])->first();
    $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
    $row['accounting_date'] = $row['accounting_date'] ? $row['accounting_date'] : date("Y-m-d");
    $row['voucher_date'] = $row['voucher_date'] ? $row['voucher_date'] : date("Y-m-d");
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
        'voucher_date'    => $row['voucher_date'],
        'accounting_date'    => $row['accounting_date'],
        'traders'    => $row['traders'],
        'subject_id'    => $subject == null ? 0 : $subject->id,  
        'object' => new ObjectDropDownListResource($subject),
      ]; 
 
      AccCashReceiptGeneralImport::setData($arr);      
      return ;
   }

}