<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccAccountSystems;
use App\Http\Model\AccSettingAccountGroup;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class AccSettingAccountGroupImport implements OnEachRow, WithHeadingRow, WithBatchInserts, WithChunkReading
{
  public function sheets(): array
    {
        return [
            new FirstSheetImport(),
          ];
    }

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function onRow(Row $row)
    {
        //dump($row);
        $code_check = AccSettingAccountGroup::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        $id = Str::uuid()->toString();
        $group = AccSettingAccountGroup::firstOrCreate([
           'id'     => $id ,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'account_group'    => $row['account_group'],      
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);
       $filter = explode(",",$row['account_filter']);
       foreach ($filter as $f){
           $account_systems = AccAccountSystems::WhereDefault('code',$f)->first();
           if($account_systems){
            $group->account_filter()->create([
                'id' => Str::uuid()->toString(),
                'account_systems' => $account_systems->id,
            ]);
           }           
       }
         
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

