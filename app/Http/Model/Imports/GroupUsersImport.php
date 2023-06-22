<?php

namespace App\Http\Model\Imports;

use App\Http\Model\GroupUsers;
use App\Http\Model\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class GroupUsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $code_check = GroupUsers::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new GroupUsers([
           'id'     => Str::uuid()->toString(),
           'company_id' => $company == null ? 0 : $company->id,
           'name'    => $row['code'],
           'code'    => $row['name'],
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
