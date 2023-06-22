<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccGroupUsers;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class AccGroupUsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
      $com = session('com');
        //dump($row);
        return new AccGroupUsers([
           'company_id' => $com->id,
           'name'    => $row['code'],
           'code'    => $row['name'],
           'active'    => $row['active'] == null ? 1 : $row['active'],
       ]);
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
