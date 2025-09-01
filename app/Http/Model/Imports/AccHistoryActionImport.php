<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccHistoryAction;
use App\Http\Model\User;
use App\Http\Model\Menu;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithLimit;

class AccHistoryActionImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
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
      $user = User::WhereDefault('username',$row['user'])->first();
      $menu = Menu::WhereDefault('code',$row['menu'])->first();
      if($row['url']){
         return new AccHistoryAction([
           'id'     => Str::uuid()->toString(),
           'url'    => $row['url'],
           'type'    => $row['type'],
           'user'    =>  $user == null ? 0 : $user->id,
           'menu'    =>  $menu == null ? 0 : $menu->id,
           'dataz'    => $row['dataz'],
       ]);
      }
        //dump($row);
       
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
