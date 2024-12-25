<?php

namespace App\Http\Model\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Illuminate\Support\Str;
use App\Http\Model\AccGeneral;
use App\Http\Model\AccNumberVoucher;
use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccObject;
use App\Classes\Convert;

class AccCashReceiptGeneralImport implements  WithHeadingRow, WithMultipleSheets, WithBatchInserts, WithLimit
{
  protected $menu;
  public function  __construct($menu)
  {
      $this->menu= $menu;
  }


  public function sheets(): array
    {
        return [
          'detail' => new FirstSheetImport()
        ];
    }

    public function batchSize(): int
    {
      return env("IMPORT_SIZE",100);
    }   
  
     public function limit(): int
     {
      return env("IMPORT_LIMIT",200);
     }

}


class FirstSheetImport implements WithMappedCells, ToModel, HasReferencesToOtherSheets, WithHeadingRow
{
    public function mapping(): array
    {
        return [
            'subject'  => 'C3',
            'traders' => 'C4',
            'description' => 'C5',
            'accounting_date' => 'K3',
            'voucher_date' => 'K4',
            'voucher' => 'K5',
        ];
    }    

    public function model(array $row)
    {
      $subject = AccObject::WhereDefault('code',$row['subject'])->first();
      $code_check = AccGeneral::WhereCheck('voucher',$row['voucher'],'id',null)->first();
      $type = 1; // 1 Receipt Cash
      $currency = 1;
      $active = 1;
      $status = 1;
      if($code_check == null){
        if($row['voucher']){
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
            $v = Convert::VoucherMasker1($voucher,$prefix);
                
            $number = $voucher->number + 1;
            $length_number = $voucher->length_number;
            if(strlen($number."") > $voucher->length_number){
              $voucher->number = 1;
              $voucher->length_number = $length_number + 1;
            }else{
              $voucher->number = $number;
            }
            $voucher->save();
          }
        }        

    return new AccGeneral([
         'id'     => Str::uuid()->toString(),
         'type'   => $type,
         'voucher'    => $row['voucher'],
         'description'    => $row['description'],
         'voucher_date'    => $row['voucher_date'],
         'accounting_date'    => $row['accounting_date'],
         'currency'    => $currency,
         'traders'    => $row['traders'],
         'subject'    => $subject == null ? 0 : $subject->id,     
         'status'    => $status, 
         'active'    => $active,
     ]);
     }
    }
