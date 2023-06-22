<?php

namespace App\Http\Model\Imports;

use App\Http\Model\AccPrintTemplate;
use App\Http\Model\Menu;
use App\Classes\Convert;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class AccPrintTemplateImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
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
        $menu = Menu::WhereDefault('code',$row['menu'])->first();
        $code_check = AccPrintTemplate::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null){
        return new AccPrintTemplate([
           'id'     => Str::uuid()->toString(),
           'menu'    => $menu == null ? 0 : $menu->id,
           'code'    => $row['code'],
           'name'    => $row['name'],
           'name_en'    => $row['name_en'],
           'date_print'    =>  Convert::dateDefaultformat($row['date_print'],'Y-m-d'),
           'content'    => $row['content'],
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
