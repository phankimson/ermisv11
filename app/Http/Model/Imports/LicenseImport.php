<?php

namespace App\Http\Model\Imports;

use App\Http\Model\License;
use App\Http\Model\Company;
use App\Http\Model\Software;
use App\Http\Model\Systems;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;
use Maatwebsite\Excel\Concerns\WithStartRow;

class LicenseImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit, WithStartRow
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
      $company = Company::WhereDefault('code',$row['company'])->first();
      $software = Software::WhereDefault('url',$row['software'])->first();
      $sys = Systems::get_systems('MAX_RANDOM');
      $code_check = License::WhereCheck('keygen',$row['keygen'],'id',null)->first();
      if($code_check == null){
        //dump($row);
        $arr = [
          'id'     => Str::uuid()->toString(),
          'date_start'    =>  Convert::dateDefaultformat($row['date_start'],'Y-m-d'),
          'date_end'    => Convert::dateDefaultformat($row['date_end'],'Y-m-d'),
          'keygen'    => $row['keygen'] == '' ? Str::random($sys->value) : $row['keygen'],
          'company_use'    => $company == null ? 0 : $company->id,
          'software_use'    => $software == null ? 0 : $software->id,
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new LicenseImport();
        $data->setData($arr);
        return new License($arr);
     }
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
