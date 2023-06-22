<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccNumberVoucher;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccNumberVoucherImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
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
        if($code_check == null){
        return new AccNumberVoucher([
           'id'     => Str::uuid()->toString(),
           'menu_id'    => $menu == null ? 0 : $menu->id,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'middle'    => $row['middle'],
           'middle_type'    => $row['middle_type'],
           'suffixes'    => $row['suffixes'],
           'suffixes_type'    => $row['suffixes_type'],
           'prefix'    => $row['prefix'],
           'number'    => $row['number'],
           'length_number'    => $row['length_number'],   
           'change_voucher'    => $row['change_voucher'] == null ? 1 : $row['change_voucher'],  
           'active'    => $row['active'] == null ? 1 : $row['active'],
            ]);
        }
    }

    public function batchSize(): int
   {
       return 1000;
   }

    public function chunkSize(): int
   {
       return 1000;
   }

}
