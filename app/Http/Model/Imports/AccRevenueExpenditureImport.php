<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccRevenueExpenditure;
use App\Http\Model\AccRevenueExpenditureType;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccRevenueExpenditureImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $type = AccRevenueExpenditureType::WhereDefault('code',$row['parent'])->first();
        $code_check = AccRevenueExpenditure::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new AccRevenueExpenditure([
           'id'     => Str::uuid()->toString(),
           'type'    => $type == null ? $type : $type->id,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'description'    => $row['description'],
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
