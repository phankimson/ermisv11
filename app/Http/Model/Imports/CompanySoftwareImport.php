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
use Maatwebsite\Excel\Concerns\WithChunkReading;

class CompanySoftwareImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $company =  Company::WhereDefault('code',$row['company'])->first();
      $software =  Software::WhereDefault('url',$row['software'])->first();
      $license =  License::WhereDefault('keygen',$row['license'])->first();
      $c = $company == null ? 0 : $company->id;
      $s = $software == null ? 0 : $software->id;
      $soft = CompanySoftware::check_company_software($c,$s);
      $db = CompanySoftware::WhereCheck('database',$row['database'],'id',null)->first();
      $hashids = new Hashids('',50);
      if($soft == 0 && $db == null){
        return new CompanySoftware([
           'id'     => Str::uuid()->toString(),
           'company_id'    => $c ,
           'software_id'    => $s,
           'license_id'    => $license == null ? 0 : $license->id,
           'free'    => Convert::IntDefaultformat($row['free']),
           'database'    => $row['database'],
           'username'    => $row['username'],
           'password'    => $hashids->encode($row['password']),
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
