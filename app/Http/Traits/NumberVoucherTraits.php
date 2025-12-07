<?php
namespace App\Http\Traits;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccCountVoucher;
use App\Classes\Convert;

trait NumberVoucherTraits
{
      public function saveNumberVoucher ($menu,$arr)
    {
         // Lưu số nhảy
            $voucher = AccNumberVoucher::get_menu($menu->id);
            // Thay đổi số nhảy theo yêu cầu DD MM YY
            $voucher_id = $voucher->id;
            $voucher_length_number = $voucher->length_number;
            $format = $voucher->format;
            $prefix = $voucher->prefix;
            if($voucher->change_voucher == 1){
              $val = Convert::dateformatArr($format,$arr->accounting_date);
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
                if($voucher->number == 0 ||  !$voucher->number ){
                  $number = 1;
                }else{
                  $number = $voucher->number + 1;
                }  
                $length_number = $voucher->length_number;
                if(strlen($number."") > $voucher->length_number){
                  $voucher->length_number = $length_number + 1;
                }
                $voucher->number = $number;
                $v = Convert::VoucherMasker1($voucher,$prefix);
                $voucher->save();
                return $v;
    }

    public function loadNumberVoucher ($menu,$arr) {
        // Lưu số nhảy
        $voucher = AccNumberVoucher::get_menu($menu->id);        
        // Thay đổi số nhảy theo yêu cầu DD MM YY
        $voucher_id = $voucher->id;
        $voucher_length_number = $voucher->length_number;
        $format = $voucher->format;
        $prefix = $voucher->prefix;
        if($voucher->change_voucher == 1){
          $val = Convert::dateformatArr($format,$arr['accounting_date']);
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
           $rs = Convert::VoucherMasker1($voucher,$prefix);     
           return $rs;   
    }

    public function loadMaskerNumberVoucher ($menu,$arr) {
        // Lưu số nhảy
        $voucher = AccNumberVoucher::get_menu($menu->id);        
        // Thay đổi số nhảy theo yêu cầu DD MM YY
        $voucher_id = $voucher->id;
        $voucher_length_number = $voucher->length_number;
        $format = $voucher->format;
        $prefix = $voucher->prefix;
        if($voucher->change_voucher == 1){
          $val = Convert::dateformatArr($format,$arr['accounting_date']);
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
           $char = "X";
           $rs = Convert::VoucherMasker2($voucher,$prefix,$char);     
           return $rs;   
    }
    
}
