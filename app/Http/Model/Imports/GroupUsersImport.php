<?php

namespace App\Http\Model\Imports;

use App\Http\Model\GroupUsers;
use App\Http\Model\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithLimit;

class GroupUsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithLimit
{
  private static $result = array();
  public function sheets(): array
    {
        return [
            new FirstSheetImport()
        ];
    }

    public function setData($arr)
    {
        array_push(self::$result,$arr);
    } 

    public function getData()
    {
        return self::$result;
    }   

  /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null    */

    public function model(array $row)
    {
      $company = Company::WhereDefault('code',$row['company'])->first();
      $code_check = GroupUsers::WhereCheck('code',$row['code'],'id',null)->first();
        if($code_check == null && $row['code']){
          $arr = [
            'id'     => Str::uuid()->toString(),
            'company_id' => $company == null ? 0 : $company->id,
            'name'    => $row['code'],
            'code'    => $row['name'],
            'active'    => $row['active'] == null ? 1 : $row['active'],
          ];
          $data = new GroupUsersImport();
          $data->setData($arr);
        return new GroupUsers($arr);
     }
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
