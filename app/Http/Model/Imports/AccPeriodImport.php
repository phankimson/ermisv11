<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccPeriod;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccPeriodImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
        $code_check = AccPeriod::WhereCheck('date',$row['date'],'id',null)->first();
        if($code_check == null){
        return new AccPeriod([
           'id'     => Str::uuid()->toString(),
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'date'    => $row['date'],   
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);
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
}
