<?php

namespace App\Http\Model\Imports;

use App\Http\Model\Error;
use App\Http\Model\Menu;
use App\Http\Model\User;
use Illuminate\Support\Str;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ErrorImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $user = User::WhereDefault('username',$row['username'])->first();
        $menu = Menu::WhereDefault('code',$row['menu'])->first();
        return new Error([
           'id'     => Str::uuid()->toString(),
           'type'    => Convert::IntDefaultformat($row['type']),
           'url'    => Convert::StringDefaultformat($row['url']),
           'user_id'    => $user == null ? 0 : $user->id,
           'menu_id'    => $menu == null ? 0 : $menu->id,
           'error'    => Convert::StringDefaultformat($row['error']),
           'check'    => $row['check'] == null ? 1 : $row['check'],
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
