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
use Maatwebsite\Excel\Concerns\WithChunkReading;

class LicenseImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $company = Company::WhereDefault('code',$row['company'])->first();
      $software = Software::WhereDefault('url',$row['software'])->first();
      $sys = Systems::get_systems('MAX_RANDOM');
      $code_check = License::WhereCheck('keygen',$row['keygen'],'id',null)->first();
      if($code_check == null){
        //dump($row);
        return new License([
           'id'     => Str::uuid()->toString(),
           'date_start'    =>  Convert::dateDefaultformat($row['date_start'],'Y-m-d'),
           'date_end'    => Convert::dateDefaultformat($row['date_end'],'Y-m-d'),
           'keygen'    => $row['keygen'] == '' ? Str::random($sys->value) : $row['keygen'],
           'company_use'    => $company == null ? 0 : $company->id,
           'software_use'    => $software == null ? 0 : $software->id,
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
