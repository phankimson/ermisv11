<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccCountVoucher;
use App\Http\Model\AccNumberVoucher;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AccCountVoucherImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
        $number_voucher = AccNumberVoucher::WhereDefault('code',$row['number_voucher'])->first();
        $arr = [
            'id'     => Str::uuid()->toString(),
            'number_voucher'    => $number_voucher == null ? 0 : $number_voucher->id,
            'format'    => $row['format'],
            'day'    => $row['day'],
            'month'    => $row['month'],
            'year'    => $row['year'],
            'number'    => $row['number'],
            'length_number'    => $row['length_number'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new AccCountVoucherImport();
        $data->setData($arr);
        return new AccCountVoucher($arr);        
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
         return env("HEADING_ROW",1);
     }
       public function startRow(): int
     {
         return env("START_ROW",2);
     }

}
