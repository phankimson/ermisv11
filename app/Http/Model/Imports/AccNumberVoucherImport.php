<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccNumberVoucher;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccNumberVoucherImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
{
  private static $result = array();
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }

    public function setData($arr)
    {
        array_push(self::$result,$arr);
    } 

    public function getData()
    {
        return self::$result;
    }   

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function model(array $row)
    {
        //dump($row);
        $code_check = AccNumberVoucher::WhereCheck('code',$row['code'],'id',null)->first();
        $menu = Menu::WhereDefault('code',$row['menu'])->first();
        $menu_general = Menu::WhereDefault('code',$row['menu_general'])->first();
        if($code_check == null && $row['code']){
            $arr = [
                'id'     => Str::uuid()->toString(),
                'menu_id'    => $menu == null ? 0 : $menu->id,
                'menu_general_id'    => $menu == null ? 0 : $menu_general->id,
                'code'    => $row['code'],
                'name'    => $row['name'],              
                'prefix'    => $row['prefix'],
                'format'    => $row['format'],
                'number'    => $row['number'],
                'length_number'    => $row['length_number'],   
                'change_voucher'    => $row['change_voucher'] == null ? 1 : $row['change_voucher'],  
                'active'    => $row['active'] == null ? 1 : $row['active'],
            ];
            $data = new AccNumberVoucherImport();
            $data->setData($arr);
            return new AccNumberVoucher($arr);
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
