<?php

namespace App\Http\Model\Imports;

use App\Http\Model\CompanySoftware;
use App\Http\Model\Company;
use App\Http\Model\Software;
use App\Http\Model\License;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Hashids\Hashids;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class CompanySoftwareImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
      $company =  Company::WhereDefault('code',$row['company'])->first();
      $software =  Software::WhereDefault('url',$row['software'])->first();
      $license =  License::WhereDefault('keygen',$row['license'])->first();
      $c = $company == null ? 0 : $company->id;
      $s = $software == null ? 0 : $software->id;
      $soft = CompanySoftware::check_company_software($c,$s);
      $db = CompanySoftware::WhereCheck('database',$row['database'],'id',null)->first();
      $hashids = new Hashids('',50);
      if($soft == 0 && $db == null && $row['database']){
        $arr = [
          'id'     => Str::uuid()->toString(),
          'company_id'    => $c ,
          'software_id'    => $s,
          'license_id'    => $license == null ? 0 : $license->id,
          'free'    => Convert::IntDefaultformat($row['free']),
          'database'    => $row['database'],
          'username'    => $row['username'],
          'password'    => $hashids->encode($row['password']),
          'active'    => $row['active'] == null ? 1 : $row['active'],
        ];
        $data = new CompanySoftwareImport();
        $data->setData($arr);
        return new CompanySoftware();
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
